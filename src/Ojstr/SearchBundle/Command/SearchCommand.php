<?php

namespace Ojstr\SearchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SearchCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('ojs:search')
                ->setDescription('basic search in all articles')
                ->addArgument('keyword', InputArgument::OPTIONAL, 'Search phrase')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $dialog = $this->getHelperSet()->get('dialog');
        $keywordArgument = $input->getArgument('keyword');
        $keyword = !$keywordArgument ?
                $dialog->ask($output, '<question>Search phrase </question> ') :
                $keywordArgument;
        $output->writeln("Searching `" . $keyword . "`");
        $output->writeln("\nResults\n");
    }

}
