<?php

namespace Ojstr\CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallTestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
                ->setName('ojs:install:travis')
                ->setDescription('Ojs test installation');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $kernel = $this->getContainer()->get('kernel');
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $application->setAutoExit(false);
        $output->writeln('<info>Ojs Test Installation</info>');

        $output->writeln('<info>Updating db schema!</info>');
        $application->run(new \Symfony\Component\Console\Input\StringInput('doctrine:schema:update --force'));

        $admin_username = 'admin';
        $admin_email = 'root@localhost.com';
        $admin_password = 'admin';

        $sb = '<fg=black;bg=green>';
        $se = '</fg=black;bg=green>';

        $output->writeln($sb . 'Inserting roles to db' . $se);
        $installCommand = new InstallCommand();
        $installCommand->insertRoles($this->getContainer(), $output);
        $output->writeln($sb . 'Inserting system admin user to db' . $se);
        $installCommand->insertAdmin($this->getContainer(), $admin_username, $admin_email, $admin_password);
        $output->writeln("\nDONE\n");
        $output->writeln("You can run "
                . "<info>sudo php app/console doctrine:fixtures:load --append -v</info> "
                . "to add sample data\n");
    }

}
