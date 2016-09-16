<?php

namespace Ojs\AnalyticsBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class StatsNormalizeCommand
 * @package Ojs\JournalBundle\Command
 */
class StatsNormalizeCommand extends ContainerAwareCommand
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
            ->setName('ojs:normalize:stats')
            ->setDescription('Normalize article, issue and journal stats')
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
        $this->io->title($this->getDescription());

        $this->io->text('Issue Normalize Started');
        $this->normalizeIssueTotalArticleView();
        $this->io->text('Issue Normalize Finished');

        $this->io->newLine();

        $this->io->text('Journal Normalize Started');

        $this->normalizeJournalTotalArticleView();
        $this->io->text('Journal Total View Normalize Finished');

        $this->normalizeJournalTotalArticleDownload();
        $this->io->text('Journal Total Download Normalize Finished');

        $this->io->newLine(2);
        $this->io->success('All process finished');
    }

    public function normalizeIssueTotalArticleView()
    {
        $rsm = new ResultSetMapping();
        $sql = <<<SQL
UPDATE issue
SET total_article_view =
  (SELECT SUM(t2.view_count)
   FROM article t2
   WHERE t2.issue_id = issue.id)
SQL;
        $query = $this->em->createNativeQuery($sql, $rsm);
        $query->getResult();
    }

    public function normalizeJournalTotalArticleView()
    {
        $rsm = new ResultSetMapping();
        $sql = <<<SQL
UPDATE journal
SET total_article_view =
  (SELECT SUM(t2.view_count)
   FROM article t2
   WHERE t2.journal_id = journal.id)
SQL;
        $query = $this->em->createNativeQuery($sql, $rsm);
        $query->getResult();
    }

    public function normalizeJournalTotalArticleDownload()
    {
        $rsm = new ResultSetMapping();
        $sql = <<<SQL
UPDATE journal
SET total_article_download =
  (SELECT SUM(t2.download_count)
   FROM article t2
   WHERE t2.journal_id = journal.id)
SQL;
        $query = $this->em->createNativeQuery($sql, $rsm);
        $query->getResult();
    }
}
