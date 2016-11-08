<?php

namespace Ojs\JournalBundle\Command;

use Doctrine\ORM\EntityManager;
use Ojs\JournalBundle\Entity\ArticleTypes;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Collections\Collection;
use Ojs\JournalBundle\Entity\Journal;

/**
 * Class JournalArticleTypeNormalizeCommand
 * @package Ojs\JournalBundle\Command
 */
class JournalArticleTypeNormalizeCommand extends ContainerAwareCommand
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
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Collection|Journal[]
     */
    private $allJournals;

    /**
     * @var Collection|ArticleTypes[]
     */
    private $allArticleTypes;

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('ojs:normalize:journal:article:types')
            ->setDescription('Normalize journal article types.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io               = new SymfonyStyle($input, $output);
        $this->container        = $this->getContainer();
        $this->em               = $this->container->get('doctrine')->getManager();
        $this->allJournals      = $this->em->getRepository('OjsJournalBundle:Journal')->findAll();
        $this->allArticleTypes  = $this->em->getRepository('OjsJournalBundle:ArticleTypes')->findAll();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->title($this->getDescription());
        $this->io->progressStart(count($this->allJournals));
        $counter = 1;
        foreach($this->allJournals as $journal){
            $this->normalizeSetting($journal);
            $this->io->progressAdvance(1);
            $counter = $counter+1;
            if($counter%50 == 0){
                $this->em->flush();
            }
        }
        $this->em->flush();
    }



    /**
     * @param Journal $journal
     */
    private function normalizeSetting(Journal $journal)
    {
        $count = $journal->getArticleTypes()->count();
        if($count > 0){
            return;
        }

        foreach ($this->allArticleTypes as $articleType) {
            $journal->addArticleType($articleType);
        }

        $this->em->persist($journal);
    }
}
