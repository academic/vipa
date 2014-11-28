<?php

namespace Ojs\CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \Ojs\UserBundle\Entity\Role;
use \Ojs\UserBundle\Entity\User;

class InstallCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('ojs:install')
                ->setDescription('Ojs first installation')
                ->addArgument('continue-on-error', InputArgument::OPTIONAL, 'Continue on error?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $keep_going = $input->getArgument('continue-on-error');
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

        $admin_username = $dialog->ask(
                $output, '<info>Set system admin username (admin) : </info>', 'admin');
        $sb = '<fg=black;bg=green>';
        $se = '</fg=black;bg=green>';
        $admin_email = $dialog->ask(
                $output, '<info>Set system admin email' .
                ' (root@localhost.com) : </info>', 'root@localhost.com');
        $admin_password = $dialog->ask(
                $output, '<info>Set system admin password (admin) </info>', 'admin');

        $output->writeln($sb . 'Inserting roles to db' . $se);
        $this->insertRoles($this->getContainer(), $output);

        $output->writeln($sb . 'Inserting system admin user to db' . $se);
        $this->insertAdmin($this->getContainer(), $admin_username, $admin_email, $admin_password);

        $output->writeln($sb . 'Insertingdefault theme record' . $se);
        $this->insertTheme($this->getContainer());

        $output->writeln("\nDONE\n");
        $output->writeln("You can run "
                . "<info>php app/console ojs:install:sampledata </info> "
                . "to add sample data\n");

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
        $user->setApiKey('MWFlZDFlMTUwYzRiNmI2NDU3NzNkZDA2MzEyNzJkNTE5NmJmZjkyZQ==');
        $role_repo = $doctrine->getRepository('OjsUserBundle:Role');
        $role_sys_admin = $role_repo->findOneByRole('ROLE_SUPER_ADMIN');
        $role_admin = $role_repo->findOneByRole('ROLE_USER');
        $role_editor = $role_repo->findOneByRole('ROLE_EDITOR');
        $role_reviewer = $role_repo->findOneByRole('ROLE_REVIEWER');

        $user->addRole($role_sys_admin);
        $user->addRole($role_admin);
        $user->addRole($role_editor);
        $user->addRole($role_reviewer);

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
