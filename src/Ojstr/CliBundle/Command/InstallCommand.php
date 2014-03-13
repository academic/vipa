<?php

namespace Ojstr\CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
        //$translator->setLocale('tr_TR');
        $output->writeln('<fg=black;bg=cyan>' . $translator->trans('Ojs installation started') . '</fg=black;bg=cyan>');

        $output->writeln('<fg=black;bg=green>' .
                $translator->trans('Inserting users to db')
                . '</fg=black;bg=green>');
        $this->insertUsers();

        $output->writeln('<fg=black;bg=green>' .
                $translator->trans('Inserting roles to db')
                . '</fg=black;bg=green>');
        $this->insertRoles();
        
        
    }

    protected function insertUsers() {
        
    }

    protected function insertRoles() {
        
    }

}
