<?php

namespace Ojs\CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class JobManagerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('ojs:jobqueue:add')
                ->setDescription('Add a new job to queue')
                ->addArgument('action', InputArgument::OPTIONAL, 'app/console command')
                ->addArgument('options', InputArgument::IS_ARRAY, 'options')
        ;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $input->getArgument('action');
        $options = $input->getArgument('options');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $kernel = $this->getContainer()->get('kernel');
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $application->setAutoExit(false);
        $output->writeln('<info>Adding command ' . $command . '</info>');
        $job = new \JMS\JobQueueBundle\Entity\Job($command, $options);
        $em->persist($job);
        $em->flush($job);

        $output->writeln("\nDONE\n");
    }

}
