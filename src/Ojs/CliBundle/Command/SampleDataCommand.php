<?php

namespace Ojs\CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Input\StringInput;

class SampleDataCommand extends ContainerAwareCommand {

    protected function configure()
    {
        $this
                ->setName('ojs:install:initial-data')
                ->setDescription('Add initial data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $kernel = $this->getContainer()->get('kernel');
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $application->setAutoExit(false);

        $output->writeln('<info>Adding initial data</info>');
        $application->run(new StringInput('h4cc_alice_fixtures:load:sets'));

        $output->writeln('<info>Adding initial workflow data</info>');
        $application->run(new StringInput('doctrine:mongodb:fixtures:load'));

        $output->writeln('<info>Recalculating precalculated fields</info>');
        $application->run(new StringInput('ojs:count:journals:subjects'));

        $output->writeln("\nDONE\n");
    }

}
