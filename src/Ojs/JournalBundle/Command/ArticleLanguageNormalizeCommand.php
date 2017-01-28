<?php

namespace Ojs\JournalBundle\Command;

use Doctrine\ORM\EntityManager;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\ArticleTypes;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Collections\Collection;
use Ojs\JournalBundle\Entity\Journal;

/**
 * Class ArticleLanguageNormalizeCommand
 * @package Ojs\JournalBundle\Command
 */
class ArticleLanguageNormalizeCommand extends ContainerAwareCommand
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
     *
     */
    protected function configure()
    {
        $this
            ->setName('ojs:normalize:article:language')
            ->setDescription('Normalize article language.')
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
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $articles = $this->em->getRepository(Article::class)->findBy(['language' => null]);

        $this->io->title($this->getDescription());
        $this->io->progressStart(count($articles));
        $counter = 1;
        foreach($articles as $article){
            $article->setLanguage($article->getJournal()->getMandatoryLang());
            $this->em->persist($article);
            $this->io->progressAdvance(1);
            $counter = $counter+1;
            if($counter%50 == 0){
                $this->em->flush();
            }
        }
        $this->em->flush();
    }
}
