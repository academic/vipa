<?php

namespace Ojs\CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \Ojs\UserBundle\Entity\Role;
use \Ojs\UserBundle\Entity\User;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Composer\Script\CommandEvent;

class InstallCommand extends ContainerAwareCommand {

    protected function configure()
    {
        $this
                ->setName('ojs:install')
                ->setDescription('Ojs first installation')
                ->addArgument('continue-on-error', InputArgument::OPTIONAL, 'Continue on error?')
        ;
    }

    public static function composer(CommandEvent $event)
    {
        $options = self::getOptions($event);
        $appDir = $options['symfony-app-dir'];
        $webDir = $options['symfony-web-dir'];

        if (!is_dir($webDir)) {
            echo 'The symfony-web-dir (' . $webDir . ') specified in composer.json was not found in ' . getcwd() . ', can not install assets.' . PHP_EOL;
            return;
        }

        static::executeCommand($event, $appDir, 'ojs:install');
    }

    protected static function getOptions(CommandEvent $event)
    {
        $options = array_merge(array(
            'symfony-app-dir' => 'app',
            'symfony-web-dir' => 'web',
            'symfony-assets-install' => 'hard',
                ), $event->getComposer()->getPackage()->getExtra());

        $options['symfony-assets-install'] = getenv('SYMFONY_ASSETS_INSTALL') ? : $options['symfony-assets-install'];

        $options['process-timeout'] = $event->getComposer()->getConfig()->get('process-timeout');

        return $options;
    }

    protected static function getPhp()
    {
        $phpFinder = new PhpExecutableFinder();
        if (!$phpPath = $phpFinder->find()) {
            throw new \RuntimeException('The php executable could not be found, add it to your PATH environment variable and try again');
        }

        return $phpPath;
    }

    protected static function executeCommand(CommandEvent $event, $appDir, $cmd, $timeout = 300)
    {
        $php = escapeshellarg(self::getPhp());
        $console = escapeshellarg($appDir . '/console');
        if ($event->getIO()->isDecorated()) {
            $console .= ' --ansi';
        }

        $process = new Process($php . ' ' . $console . ' ' . $cmd, null, null, null, $timeout);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf('An error occurred when executing the "%s" command.', escapeshellarg($cmd)));
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $keep_going = $input->getArgument('continue-on-error');
        $sb = '<fg=black;bg=green>';
        $se = '</fg=black;bg=green>';
        $translator = $this->getContainer()->get('translator');
        $dialog = $this->getHelperSet()->get('dialog');
        $kernel = $this->getContainer()->get('kernel');
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $application->setAutoExit(false);
//$translator->setLocale('tr_TR');
        $output->writeln($this->printWelcome());
        $output->writeln('<info>' .
                $translator->trans('ojs.install.title') .
                '</info>');

        if (!$dialog->askConfirmation(
                        $output, '<question>' .
                        $translator->trans("ojs.install.confirm") .
                        ' (y/n) : </question>', true
                )) {
            return;
        }

        $command2 = 'doctrine:schema:update --force';
        $output->writeln('<info>Updating db schema!</info>');
        $application->run(new \Symfony\Component\Console\Input\StringInput($command2));

        $location = $this->getContainer()->get('kernel')->getRootDir().'/../src/Okulbilisim/LocationBundle/Resources/data/location.sql';
        $locationSql = \file_get_contents($location);
        $command3 = 'doctrine:query:sql "'.$locationSql.'"';
        $application->run(new \Symfony\Component\Console\Input\StringInput($command3));
        $output->writeln("Locations inserted.");

        $output->writeln($sb . 'Inserting roles to db' . $se);
        $this->insertRoles($this->getContainer(), $output);

        $admin_username = $dialog->ask(
                $output, '<info>Set system admin username (admin) : </info>', 'admin');
        $admin_email = $dialog->ask(
                $output, '<info>Set system admin email' .
                ' (root@localhost.com) : </info>', 'root@localhost.com');
        $admin_password = $dialog->ask(
                $output, '<info>Set system admin password (admin) </info>', 'admin');

        $output->writeln($sb . 'Inserting system admin user to db' . $se);
        $this->insertAdmin($this->getContainer(), $admin_username, $admin_email, $admin_password); 

        $output->writeln($sb . 'Inserting default theme record' . $se);
        $this->insertTheme($this->getContainer());

        $output->writeln("\nDONE\n");
        $output->writeln("You can run "
                . "<info>php app/console ojs:install:initial-data </info> "
                . "to add initial data\n");
    } 

    /**
     * add default roles
     * @return boolean
     */
    public function insertRoles($container, OutputInterface $output)
    {
        $doctrine = $container->get('doctrine');
        $em = $doctrine->getManager();
        $roles = $container->getParameter('roles');
        $role_repo = $doctrine->getRepository('OjsUserBundle:Role');
        foreach ($roles as $role) {
            $new_role = new Role();
            $check = $role_repo->findOneByRole($role['role']);
            if (!empty($check)) {
                $output->writeln('<error> This role record already exists on db </error> : <info>' .
                        $role['role'] . '</info>');
                continue;
            }
            $output->writeln('<info>Added : ' . $role['role'] . '</info>');
            $new_role->setName($role['desc']);
            $new_role->setRole($role['role']);
            $new_role->setIsSystemRole($role['isSystemRole']);

            $em->persist($new_role);
        }

        return $em->flush();
    }

    public function insertAdmin($container, $username, $email, $password)
    {
        $doctrine = $container->get('doctrine');
        $em = $doctrine->getManager();

        $factory = $container->get('security.encoder_factory');
        $user = new User();

        $encoder = $factory->getEncoder($user);
        $pass_encoded = $encoder->encodePassword($password, $user->getSalt());
        $user->setEmail($email);
        $user->setPassword($pass_encoded);
        $user->setUsername($username);
        $user->setIsActive(true);
        $user->generateApiKey();

        $role_repo = $doctrine->getRepository('OjsUserBundle:Role');
        $user->addRole($role_repo->findOneByRole('ROLE_SUPER_ADMIN'));
        $user->addRole($role_repo->findOneByRole('ROLE_USER'));
        $user->addRole($role_repo->findOneByRole('ROLE_EDITOR'));
        $user->addRole($role_repo->findOneByRole('ROLE_REVIEWER'));

        $em->persist($user);
        $em->flush();
    }

    public function insertTheme($container)
    {
        $em = $container->get('doctrine')->getManager();
        $theme = new \Ojs\JournalBundle\Entity\Theme();
        $theme->setName("default");
        $theme->setTitle('Ojs');
        $em->persist($theme);
        $em->flush();
    }

    protected function printWelcome()
    {
        return '';
    }

}
