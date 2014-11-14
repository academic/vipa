<?php

namespace Ojs\CliBundle\Command\Jobs;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DropExpiredProxyUsersCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('ojs:users:drop-proxies')
                ->setDescription('Check all proxy users\' ttl and drop expired ones.');
    }

    /**
     * @todo not implemented yet
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Checking all proxy users</info>\n");
        $kernel = $this->getContainer()->get('kernel');
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $application->setAutoExit(false);
    }

}
