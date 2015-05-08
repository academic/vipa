<?php

namespace Ojs\CliBundle\Command\Jobs;

use Ojs\JournalBundle\Entity\Subject;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CountJournalsForSubjectsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('ojs:count:journals:subjects')
                ->setDescription('Count journals for all subjects and update all Subject entities\' total_journal_count field.');
    }

    /**
     * @todo not implemented yet
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Counting all journals grouped by Subjects</info>\n");
        $kernel = $this->getContainer()->get('kernel');

        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();
        $subjects = $em->getRepository('OjsJournalBundle:Subject')->findAll();

        foreach ($subjects as $subject) {
            /** @var Subject $subject */
            if($subject->getJournals()) {
	    	$count = $subject->getJournals()->count();
            	$subject->setTotalJournalCount($count);
                $subject->setUpdatedBy("System");
            	$em->persist($subject);
            	$em->flush();
	    }
            echo ".";
        }
    }

}
