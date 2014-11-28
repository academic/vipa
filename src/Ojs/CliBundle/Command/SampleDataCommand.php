<?php

namespace Ojs\CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SampleDataCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('ojs:install:sampledata')
                ->setDescription('Add sample fake data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $kernel = $this->getContainer()->get('kernel');
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $output->writeln('<info>Adding fake data</info>');
        $application->run(new \Symfony\Component\Console\Input\StringInput('h4cc_alice_fixtures:load:sets'));
        $output->writeln("\nDONE\n");
    }

}
