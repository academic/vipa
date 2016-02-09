<?php

namespace Ojs\CoreBundle\Command;

use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheAdminStaticsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('cache:admin:statics')
            ->setDescription('Cache Admin Statics');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createStats($output);
    }

    private function createStats(OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine');
        $cache = $container->get('file_cache');
        $generator = $container->get('ojs.graph.data.generator');
        $journals = $em->getRepository('OjsJournalBundle:Journal')->findAll();

        $lastMonth = ['x'];
        for($i = 0; $i < 30; $i++) {
            $lastMonth[] = date($generator->getDateFormat(), strtotime('-' . $i . ' days'));
        }

        $slicedLastMonth = array_slice($lastMonth, 1);

        $articles = $em
            ->getRepository('OjsJournalBundle:Article')
            ->findBy(['journal' => $journals]);

        $issues = $em
            ->getRepository('OjsJournalBundle:Issue')
            ->findBy(['journal' => $journals]);

        $json = [
            'dates' => $lastMonth,
            'journalViews' => $generator->generateJournalBarChartData($journals, $slicedLastMonth),
            'articleViews' => $generator->generateArticleBarChartData($articles, $slicedLastMonth),
            'issueFileDownloads' => $generator->generateIssueFilePieChartData($issues, $slicedLastMonth),
            'articleFileDownloads' => $generator->generateArticleFilePieChartData($articles, $slicedLastMonth),
        ];

        $data = [
            'stats' => json_encode($json),
            'journals' => $generator->generateJournalViewsData($journals),
            'articles' => $generator->generateArticleViewsData($articles),
            'issueFiles' => $generator->generateIssueFileDownloadsData($issues),
            'articleFiles' => $generator->generateArticleFileDownloadsData($issues),
            'journalsMonthly' => $generator->generateJournalViewsData($journals, $slicedLastMonth),
            'articlesMonthly' => $generator->generateArticleViewsData($articles, $slicedLastMonth),
            'issueFilesMonthly' => $generator->generateIssueFileDownloadsData($issues, $slicedLastMonth),
            'articleFilesMonthly' => $generator->generateArticleFileDownloadsData($articles, $slicedLastMonth),
        ];

        $output->writeln('Removing cache for admin_statics');
        $cache->delete('admin_statics');

        $output->writeln('Saving cache for admin_statics');
        $cache->save('admin_statics', $data);

        $output->writeln('all done');
        return true;
    }
}
