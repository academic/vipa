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
        $output->writeln('<info>' .
                $translator->trans('Ojs Installation') .
                '</info>');

        if (!$dialog->askConfirmation(
                        $output, '<question>' .
                        $translator->trans('Confirm installation?') .
                        ' (y/n) : </question>', true
                )) {
            return;
        }

        $admin_username = $dialog->ask(
                $output, '<info>' .
                $translator->trans('Set the system admin username') .
                ' (admin) : </info>', 'admin');
        $admin_email = $dialog->ask(
                $output, '<info>' .
                $translator->trans('Set the system admin email') .
                ' (root@localhost.com) : </info>', 'root@localhost.com');
        $admin_password = $dialog->askHiddenResponse(
                $output, '<info>' .
                $translator->trans('Set the system admin password') .
                ' (admin) : </info>', 'admin');


        $output->writeln('<fg=black;bg=green>' .
                $translator->trans('Inserting roles to db')
                . '</fg=black;bg=green>');
        $this->insertRoles();


        $output->writeln('<fg=black;bg=green>' .
                $translator->trans('Inserting system admin user to db') .
                '</fg=black;bg=green>');
        $this->insertAdmin($admin_username, $admin_email, $admin_password);
    }

    protected function insertRoles() {

//        $role = new Role();
//        $role->setName('Super Editor');
//        $role->setRole('ROLE_SUPER_EDITOR');
    }

    protected function insertAdmin($username, $email, $password) {
        //$factory = $this->get('security.encoder_factory');
//        $user = new User();
//
//        $encoder = $factory->getEncoder($user);
//        $pass_encoded = $encoder->encodePassword($password, $user->getSalt());
//        $user->setEmail($email);
//        $user->setPassword($pass_encoded);
//        $user->setUsername($username);
//        $user->setIsActive(1);
//
//
//        $em = $this->getDoctrine()->getEntityManager();
//        $em->persist($user);
//        $em->flush();
//
//
//
//        $manager->persist($userAdmin);
//        $manager->flush();
    }

}
