<?php

namespace Ojs\CoreBundle\Command;

use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

class NormalizeLastIssuesCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var SymfonyStyle
     */
    private $io;

    protected function configure()
    {
        $this
            ->setName('ojs:normalize:last:issues')
            ->setDescription('Normalize last issues.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->em           = $this->getContainer()->get('doctrine')->getManager();
        $this->io           = new SymfonyStyle($input, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->title($this->getDescription());

        $allJournals = $this->getAllJournals();
        $this->io->progressStart(count($allJournals));
        foreach($allJournals as $journal){
            $this->normalizeLastIssuesByJournal($journal);
        }
    }

    private function getAllJournals()
    {
        return $this->em->getRepository('OjsJournalBundle:Journal')->findAll();
    }

    /**
     * @param Journal $journal
     * @return bool|null
     */
    private function normalizeLastIssuesByJournal(Journal $journal)
    {
        $this->io->newLine();
        $this->io->text('normalizing last issue for '.$journal->getTitle());
        $this->io->progressAdvance();
        $findLastIssue = $this->em->getRepository('OjsJournalBundle:Issue')->findOneBy([
            'journal' => $journal,
            'lastIssue' => true
        ]);
        if($findLastIssue){
            return true;
        }
        /** @var Issue|null $getLogicalLastIssue */
        $getLogicalLastIssue = $this->em->getRepository('OjsJournalBundle:Issue')->getLastIssueByJournal($journal);
        if($getLogicalLastIssue == null){
            return null;
        }
        $getLogicalLastIssue->setLastIssue(true);
        $this->em->flush();
    }
}
