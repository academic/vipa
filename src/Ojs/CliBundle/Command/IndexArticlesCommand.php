<?php

namespace Ojs\CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexArticlesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
                ->setName('search:index')
                ->setDescription('Index all articles or given journal\'s articles to search engine ')
                ->addArgument('journal-id', InputArgument::OPTIONAL, 'Journal id')
        ;
    }

    /**
     * Not implemented yet
     *  @todo not implemented yet
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $journalId = $input->getArgument('journal-id');
        $dialog = $this->getHelperSet()->get('dialog');
        $kernel = $this->getContainer()->get('kernel');
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $application->setAutoExit(false);
        $output->writeln('<info>Index ' . ($journalId ? 'of journal#' . $journalId : 'all') . ' articles</info>');

        $output->writeln("\nDONE\n");
    }

}
