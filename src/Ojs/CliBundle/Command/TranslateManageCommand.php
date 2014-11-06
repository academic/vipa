<?php

namespace Ojs\CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScheduledTasksCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
                ->setName('scheduled:run')
                ->setDescription('check and run all scheduled jobs. You can pass job-id if you know.')
                ->addArgument('job-id', InputArgument::OPTIONAL, 'Spesific job id')
        ;
    }

    /**
     * Not implemented
     * @todo not implemented yet
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $jobId = $input->getArgument('job-id');
        $kernel = $this->getContainer()->get('kernel');
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $application->setAutoExit(false);
        $output->writeln('<info>Run Scheduled Jobs : ' . ($jobId ? ' job#' . $jobId : 'All') . '</info>');

        $output->writeln("\nDONE\n");
    }

}
