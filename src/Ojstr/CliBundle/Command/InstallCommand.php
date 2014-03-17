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
    }

    protected function insertRoles(OutputInterface $output) {
        $doctrine = $this->getContainer()->get('doctrine');
        $translator = $this->getContainer()->get('translator');
        $em = $doctrine->getEntityManager();
        $roles = array(
            'ROLE_ADMIN' => 'ADministrator',
            'ROLE_SYSTEM_ADMIN' => 'System Administrator',
            'ROLE_JOURNAL_MANAGER' => 'Journal Manager',
            'ROLE_SUBSCRIPTION_MANAGER' => 'Subscription Manager',
            'ROLE_EDITOR' => 'Editor',
            'ROLE_SECTION_EDITOR' => 'Section Editor',
            'ROLE_LAYOUT_EDITOR' => 'Layout Editor',
            'ROLE_REVIEWER' => 'Reviewer',
            'ROLE_COPYEDITOR ' => 'Copyeditor',
            'ROLE_PROOFREADER' => 'Proofreader',
            'ROLE_AUTHOR' => 'Author',
            'ROLE_READER' => 'Reader',
        );
        $role_repo = $doctrine->getRepository('OjstrUserBundle:Role');
        foreach ($roles as $role => $role_name) {
            $new_role = new Role();
            $check = $role_repo->findOneByRole($role);
            if (!empty($check)) {
                $output->writeln('<error>' .
                        $translator->trans('This role record already exists on db') .
                        '</error>' . ' : <info>' . $role . '</info>');
                continue;
            }
            $output->writeln('<info>' . $translator->trans('Added ') . ' : ' . $role . '</info>');
            $new_role->setName($role_name);
            $new_role->setRole($role);
            $em->persist($new_role);
        }
        return $em->flush();
    }

    protected function insertAdmin($username, $email, $password) {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();

        $factory = $this->getContainer()->get('security.encoder_factory');
        $user = new User();

        $encoder = $factory->getEncoder($user);
        $pass_encoded = $encoder->encodePassword($password, $user->getSalt());
        $user->setEmail($email);
        $user->setPassword($pass_encoded);
        $user->setUsername($username);
        $user->setIsActive(1);
        $role = $doctrine->getRepository('OjstrUserBundle:Role')->findOneByRole('ROLE_SYSTEM_ADMIN');
        $user->addRole($role);

        $em->persist($user);
        $em->flush();
    }

}
