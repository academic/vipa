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
                ->addArgument('continue-on-error', InputArgument::OPTIONAL, 'Continue on error?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $translator = $this->getContainer()->get('translator');
        $dialog = $this->getHelperSet()->get('dialog');
        //$translator->setLocale('tr_TR'); 
        $keyword = $dialog->ask($output, '<question>' . $translator->trans('Search phrase') . ' :</question> ');

        $output->writeln("\nResults\n");
    }

}
