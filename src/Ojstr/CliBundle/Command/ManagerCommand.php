<?php

namespace Ojstr\CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ManagerCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('ojs:install')
                ->setDescription('Ojs first installation')
                ->addArgument('title', InputArgument::OPTIONAL, 'What is your OJS title?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $title = $input->getArgument('title');
        $output->writeln('<info>Your ojs title will be : ' . $title . '</info>');

        if ($output->isQuiet()) {
            
        }
        if ($output->isVerbose()) {
            
        }
        if ($output->isVeryVerbose()) {
            
        }
        if ($output->isDebug()) {
            
        }
    }

}
