<?php

namespace Ojstr\CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \Ojstr\UserBundle\Entity\Role;
use \Ojstr\UserBundle\Entity\User;
use \Ojstr\UserBundle\Entity\RoleRepository;

class InstallCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('ojs:install')
                ->setDescription('Ojs first installation')
                ->addArgument('continue-on-error', InputArgument::OPTIONAL, 'Continue on error?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $keep_going = $input->getArgument('continue-on-error');
        $translator = $this->getContainer()->get('translator');
        $dialog = $this->getHelperSet()->get('dialog');

        //$translator->setLocale('tr_TR');
        $output->writeln($this->printWelcome());
        $output->writeln('<info>' .
                $translator->trans('Ojs Installation') .
                '</info>');

        if (!$dialog->askConfirmation(
                        $output, '<question>' .
                        $translator->trans("This will create a database and new schemas. \n"
                                . "Confirm installation?") .
                        ' (y/n) : </question>', true
                )) {
            return;
        }

        $kernel = $this->getContainer()->get('kernel');
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $application->setAutoExit(false);
        $command1 = 'doctrine:database:create';
        $command2 = 'doctrine:schema:update --force';

        $output->writeln('<info>' .
                $translator->trans('Creating db schema!') .
                '</info>');
        $application->run(new \Symfony\Component\Console\Input\StringInput($command1));
        $output->writeln('<info>' .
                $translator->trans('Updating db schema!') .
                '</info>');
        $application->run(new \Symfony\Component\Console\Input\StringInput($command2));


        $admin_username = $dialog->ask(
                $output, '<info>' .
                $translator->trans('Set system admin username') .
                ' (admin) : </info>', 'admin');
        $sb = '<fg=black;bg=green>';
        $se = '</fg=black;bg=green>';

        $admin_email = $dialog->ask(
                $output, '<info>' .
                $translator->trans('Set system admin email') .
                ' (root@localhost.com) : </info>', 'root@localhost.com');
        $admin_password = $dialog->askHiddenResponse(
                $output, '<info>' .
                $translator->trans('Set system admin password') .
                ' (admin) : </info>', 'admin');

        $output->writeln($sb . $translator->trans('Inserting roles to db') . $se);
        $this->insertRoles($output);

        $output->writeln($sb . $translator->trans('Inserting system admin user to db') . $se);
        $this->insertAdmin($admin_username, $admin_email, $admin_password);
        $output->writeln("\nDONE\n");
        $output->writeln("You can run "
                . "<info>sudo php app/console doctrine:fixtures:load --append -v</info> "
                . "to add sample data\n");
    }

    protected function insertRoles(OutputInterface $output) {
        $doctrine = $this->getContainer()->get('doctrine');
        $translator = $this->getContainer()->get('translator');
        $em = $doctrine->getManager();
        $roles = $this->getContainer()->getParameter('roles');
        $role_repo = $doctrine->getRepository('OjstrUserBundle:Role');
        foreach ($roles as $role) {
            $new_role = new Role();
            $check = $role_repo->findOneByRole($role['role']);
            if (!empty($check)) {
                $output->writeln('<error>' .
                        $translator->trans('This role record already exists on db') .
                        '</error>' . ' : <info>' . $role['role'] . '</info>');
                continue;
            }
            $output->writeln('<info>' . $translator->trans('Added ') . ' : ' . $role['role'] . '</info>');
            $new_role->setName($role['desc']);
            $new_role->setRole($role['role']);
            $em->persist($new_role);
        }
        return $em->flush();
    }

    protected function insertAdmin($username, $email, $password) {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        $factory = $this->getContainer()->get('security.encoder_factory');
        $user = new User();

        $encoder = $factory->getEncoder($user);
        $pass_encoded = $encoder->encodePassword($password, $user->getSalt());
        $user->setEmail($email);
        $user->setPassword($pass_encoded);
        $user->setUsername($username);
        $user->setIsActive(TRUE);
        $role_repo = $doctrine->getRepository('OjstrUserBundle:Role');
        $role_sys_admin = $role_repo->findOneByRole('ROLE_SUPER_ADMIN');
        $role_admin = $role_repo->findOneByRole('ROLE_USER');
        $user->addRole($role_sys_admin);
        $user->addRole($role_admin);

        $em->persist($user);
        $em->flush();
    }

    protected function printWelcome() {
        return '
 _____    _____  ____       
/\  __`\ /\___ \/\  _`\     
\ \ \/\ \\/__/\ \ \,\L\_\   
 \ \ \ \ \  _\ \ \/_\__ \   
  \ \ \_\ \/\ \_\ \/\ \L\ \ 
   \ \_____\ \____/\ `\____\
    \/_____/\/___/  \/_____/
                            
                            ';
    }

}
