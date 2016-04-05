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

class DeleteJournalCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var Journal
     */
    private $journal;

    protected function configure()
    {
        $this
            ->setName('ojs:delete:journal')
            ->addArgument('journalId', InputArgument::REQUIRED, 'Journal to remove support an journal_id')
            ->setDescription('Delete a journal with all relations.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->em   = $this->getContainer()->get('doctrine')->getManager();
        $this->io   = new SymfonyStyle($input, $output);
        $this->refreshJournal($input);
        $this->getContainer()->get('stof_doctrine_extensions.listener.blameable')->setUserValue('cli');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->title($this->getDescription());

        $this->io->text("removing articles\n");
        $this->removeArticles();

        $this->io->newLine(2);

        $this->io->text("refresh journal and remove issues\n");
        $this->removeIssues($input);

        $this->io->newLine(1);
        $this->io->text("refresh journal and remove journal totally");
        $this->refreshJournal($input);
        $this->em->remove($this->journal);
        $this->em->flush();
        $this->io->success('successfully removed journal');
    }

    private function refreshJournal(InputInterface $input)
    {
        $this->journal      = $this->em->getRepository('OjsJournalBundle:Journal')->find($input->getArgument('journalId'));
        if(!$this->journal){
            throw new \LogicException('Please support valid journal id');
        }
    }

    private function removeArticles()
    {
        $journalArticles = $this->em->getRepository('OjsJournalBundle:Article')->findBy([
            'journal' => $this->journal
        ]);
        $this->io->progressStart(count($journalArticles));
        foreach($journalArticles as $article){
            $this->io->progressAdvance();
            $this->em->remove($article);
        }
        $this->em->flush();
    }

    private function removeIssues(InputInterface $input)
    {
        $this->refreshJournal($input);
        $journalIssues = $this->em->getRepository('OjsJournalBundle:Issue')->findBy([
            'journal' => $this->journal
        ]);
        $this->io->progressStart(count($journalIssues));
        foreach($journalIssues as $issue){
            $this->io->progressAdvance();
            $this->em->remove($issue);
        }
        $this->em->flush();
    }
}
