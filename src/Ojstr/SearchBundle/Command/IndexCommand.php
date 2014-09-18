<?php

namespace Ojstr\SearchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
                ->setName('ojs:search:index')
                ->setDescription('Index articles')
                ->addArgument('articleId', InputArgument::OPTIONAL, 'Only index given article')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $articleId = $input->getArgument('articleId');
        $output->writeln("Indexing" . ($articleId ? " articleId:" . $articleId . "" : " all articles"));
        $output->writeln("\nResults\n");
    }

}
