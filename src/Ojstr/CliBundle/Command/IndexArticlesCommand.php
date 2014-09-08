<?php

namespace Ojstr\CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \Ojstr\UserBundle\Entity\Role;
use \Ojstr\UserBundle\Entity\User;

class IndexArticlesCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('search:index')
                ->setDescription('Index all articles or given journal\'s articles to search engine ')
                ->addArgument('journal-id', InputArgument::OPTIONAL, 'Journal id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $journalId = $input->getArgument('journal-id');
        $dialog = $this->getHelperSet()->get('dialog');
        $kernel = $this->getContainer()->get('kernel');
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $application->setAutoExit(false);
        $output->writeln('<info>Index ' . ($journalId ? 'of journal#' . $journalId : 'all') . ' articles</info>');



        $output->writeln("\nDONE\n");
    }

}
