<?php

namespace Vipa\CoreBundle\Command;

use Vipa\AdminBundle\Entity\AdminPage;
use Vipa\CoreBundle\Events\CoreEvent;
use Vipa\CoreBundle\Events\CoreEvents;
use Vipa\UserBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class InstallCommand extends ContainerAwareCommand
{
    public static function composer(CommandEvent $event)
    {
        $options = self::getOptions($event);
        $appDir = $options['symfony-app-dir'];
        $webDir = $options['symfony-web-dir'];

        if (!is_dir($webDir)) {
            echo 'The symfony-web-dir (' . $webDir . ') specified in composer.json was not found in ' . getcwd() . ', can not install assets.' . PHP_EOL;

            return;
        }

        static::executeCommand($event, $appDir, 'vipa:install');
    }

    protected static function getOptions(CommandEvent $event)
    {
        $options = array_merge(
            array(
                'symfony-app-dir' => 'app',
                'symfony-web-dir' => 'web',
                'symfony-assets-install' => 'hard',
            ),
            $event->getComposer()->getPackage()->getExtra()
        );

        $options['symfony-assets-install'] = getenv('SYMFONY_ASSETS_INSTALL') ?: $options['symfony-assets-install'];

        $options['process-timeout'] = $event->getComposer()->getConfig()->get('process-timeout');

        return $options;
    }

    protected static function executeCommand(CommandEvent $event, $appDir, $cmd, $timeout = 300)
    {
        $php = escapeshellarg(self::getPhp());
        $console = escapeshellarg($appDir . '/console');
        if ($event->getIO()->isDecorated()) {
            $console .= ' --ansi';
        }

        $process = new Process($php . ' ' . $console . ' ' . $cmd, null, null, null, $timeout);
        $process->run(
            function ($type, $buffer) {
                echo $buffer;
            }
        );
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(
                sprintf('An error occurred when executing the "%s" command.', escapeshellarg($cmd))
            );
        }
    }

    protected static function getPhp()
    {
        $phpFinder = new PhpExecutableFinder();
        if (!$phpPath = $phpFinder->find()) {
            throw new \RuntimeException(
                'The php executable could not be found, add it to your PATH environment variable and try again'
            );
        }

        return $phpPath;
    }

    protected function configure()
    {
        $this
            ->setName('vipa:install')
            ->setDescription('Vipa first installation')
            ->addOption('no-role', null, InputOption::VALUE_NONE, 'Without role data')
            ->addOption('no-admin', null, InputOption::VALUE_NONE, 'Without admin records')
            ->addOption('no-location', null, InputOption::VALUE_NONE, 'Without location data')
            ->addOption('no-acl', null, InputOption::VALUE_NONE, 'Without ACL Data')
            ->addOption('fix-acl', null, InputOption::VALUE_NONE, 'Fix ACL structure')
            ->addOption('no-page', null, InputOption::VALUE_NONE, 'Without default pages')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sb = '<fg=black;bg=green>';
        $se = '</fg=black;bg=green>';
        $translator = $this->getContainer()->get('translator');
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->getContainer()->get('event_dispatcher');

        /** @var HelperSet $helperSet */
        $helperSet = $this->getHelperSet();
        /** @var DialogHelper $dialog */
        $dialog = $helperSet->get('dialog');
        $kernel = $this->getContainer()->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);
        //$translator->setLocale('tr_TR');
        $output->writeln($this->printWelcome());
        $output->writeln(
            '<info>' .
            $translator->trans('vipa.install.title') .
            '</info>'
        );

        if (!$input->getOption('no-interaction')) {
            if (!$dialog->askConfirmation(
                $output,
                '<question>' .
                $translator->trans('vipa.install.confirm') .
                ' (y/n) : </question>',
                true
            )
            ) {
                return;
            }
        }

        if ($input->getOption('no-interaction')) {
            $command1 = 'doctrine:schema:create';
            $output->writeln('<info>Creating db schema!</info>');
            $application->run(new StringInput($command1));
        }

        $command2 = 'doctrine:schema:update --force';
        $output->writeln('<info>Updating db schema!</info>');
        $application->run(new StringInput($command2));

        if (!$input->getOption('no-location')) {
            try {
                $connection = $this->getContainer()->get('doctrine')->getConnection();
                $connectionParams = $connection->getParams();
                $location = $this
                        ->getContainer()->get('kernel')
                        ->getRootDir() . '/../vendor/bulutyazilim/location-bundle/Resources/data/location.sql';
                $locationSql = file_get_contents($location);

                $driver = $connectionParams['driver'];

                if ($driver == 'pdo_mysql') {
                    $locationSql = 'SET foreign_key_checks = 0;' . $locationSql . 'SET foreign_key_checks = 1;';
                }elseif($driver == 'pdo_sqlite'){
                    $locationSql = 'PRAGMA foreign_keys = OFF;' . $locationSql . 'PRAGMA foreign_keys = ON;';
                }

                $connection->exec($locationSql);
                $output->writeln('Locations inserted.');
            } catch (\Exception $e) {
                $output->writeln('Location insertion failed. --> '. $e->getMessage());
            }
        }

        if (!$input->getOption('no-role')) {
            $output->writeln($sb . 'Inserting roles to db' . $se);
            $this->insertRoles($output);

            if (!$input->getOption('no-interaction')) {

                if (!$input->getOption('no-admin')) {
                    $admin_username = $dialog->ask(
                        $output,
                        '<info>Set system admin username (admin) : </info>',
                        'admin'
                    );
                    $admin_email = $dialog->ask(
                        $output,
                        '<info>Set system admin email' .
                        ' (root@localhost.com) : </info>',
                        'root@localhost.com'
                    );
                    $admin_password = $dialog->ask(
                        $output,
                        '<info>Set system admin password (admin) </info>',
                        'admin'
                    );

                    $output->writeln($sb . 'Inserting system admin user to db' . $se);
                    $this->insertAdmin($admin_username, $admin_email, $admin_password);
                }
            } else {
                $output->writeln($sb . 'Inserting system admin user to db' . $se);
                $this->insertAdmin('admin', 'admin@localhost', 'admin');
            }
        }

        if (!$input->getOption('no-acl')) {
            $output->writeln($sb . 'Inserting default ACL records' . $se);
            $this->insertAcls();
        }

        if ($input->getOption('fix-acl')) {
            $output->writeln($sb . 'Fixing ACL Records' . $se);
            $this->fixAcls($output);
        }

        if (!$input->getOption('no-page')) {
            $output->writeln($sb . 'Creating default pages' . $se);
            $this->createDefaultPages();
        }

        $output->writeln("\nDONE\n");
        $output->writeln(
            "You can run"
            . " <info>php app/console vipa:install:samples</info> "
            . "to add some sample data.\n"
        );
        $event = new CoreEvent();
        $dispatcher->dispatch(CoreEvents::VIPA_INSTALL_BASE, $event);
    }

    protected function printWelcome()
    {
        return '';
    }

    /**
     * @param OutputInterface $output
     */
    public function insertRoles(OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();
        $roles = $this->getContainer()->getParameter('roles');
        $role_repo = $doctrine->getRepository('VipaUserBundle:Role');
        foreach ($roles as $role) {
            $new_role = new Role();
            $check = $role_repo->findOneBy(array('role' => $role['role']));
            if (!empty($check)) {
                $output->writeln(
                    '<error> This role record already exists on db </error> : <info>' .
                    $role['role'] . '</info>'
                );
                continue;
            }
            $output->writeln('<info>Added : ' . $role['role'] . '</info>');
            $new_role->setName($role['desc']);
            $new_role->setRole($role['role']);

            $em->persist($new_role);
        }
        $em->flush();
    }

    /**
     * @param $username
     * @param $email
     * @param $password
     */
    public function insertAdmin($username, $email, $password)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        $user = $em->getRepository('VipaUserBundle:User')->findOneBy(
            array('email' => $email)
        );
        if (is_null($user)) {
            $user = $em->getRepository('VipaUserBundle:User')->findOneBy(
                array('username' => $username)
            );
        }
        if (is_null($user)) {
            $kernel = $this->getContainer()->get('kernel');
            $application = new Application($kernel);
            $application->setAutoExit(false);
            $application->run(
                new StringInput(
                    'fos:user:create --super-admin ' . $username . ' ' . $email . ' ' . $password
                )
            );
        }
    }

    protected function insertAcls()
    {
        $this->getContainer()->get('core.acl_fixer')->insertAcls();
    }

    protected function fixAcls(OutputInterface $output)
    {
        $sb = '<fg=black;bg=green>';
        $se = '</fg=black;bg=green>';
        $this->getContainer()->get('core.acl_fixer')->fixAcl();
        $output->writeln(
            $sb . 'ACL FIXED! ' . $se
        );
    }

    protected function createDefaultPages()
    {
        $pages = [
            [
                ['about', 'About', 'About page content goes here.', 'en'],
                ['about', 'Hakkında', 'Hakkında sayfası içeriği.', 'tr'],
            ],
            [
                ['privacy', 'Privacy', 'Privacy page content goes here.', 'en'],
                ['privacy', 'Privacy', 'Privacy sayfası içeriği.', 'tr'],
            ],
            [
                ['faq', 'FAQ', 'A list of frequently answered questions goes here.', 'en'],
                ['faq', 'SSS', 'Sıkça sorulan sorular içeriği.', 'tr'],
            ],
            [
                ['tos', 'Terms of Service', 'TOS page content goes here.', 'en'],
                ['tos', 'Terms of Service', 'TOS sayfası içeriği.', 'tr'],
            ],
        ];

        $em = $this->getContainer()->get('doctrine')->getManager();

        foreach ($pages as $page) {
            $entity = $em->getRepository('VipaAdminBundle:AdminPage')->findOneBy(['slug' => $page[0][0]]);

            if (!$entity) {
                $entity = new AdminPage();
                $entity->setVisible(true);
                $entity->setCurrentLocale($page[0][3]);
                $entity->setSlug($page[0][0]);
                $entity->setTitle($page[0][1]);
                $entity->setBody($page[0][2]);

                $entity->setCurrentLocale($page[1][3]);
                $entity->setSlug($page[1][0]);
                $entity->setTitle($page[1][1]);
                $entity->setBody($page[1][2]);

                $em->persist($entity);
            }
        }

        $em->flush();
    }
}
