<?php

namespace Ojs\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheAdminStaticsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ojs:cache:admin:statics')
            ->setDescription('Cache Admin Statics')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createStats($output);
    }

    private function createStats(OutputInterface $output)
    {
        $container = $this->getContainer();
        $cache = $container->get('file_cache');
        $generator = $container->get('ojs.graph.data.generator');

        $lastMonth = ['x'];
        for($i = 0; $i < 30; $i++) {
            $lastMonth[] = date($generator->getDateFormat(), strtotime('-' . $i . ' days'));
        }
        $output->writeln('set all times');
        $slicedLastMonth = array_slice($lastMonth, 1);
        $output->writeln('slice times');

        $json = [
            'dates' => $lastMonth,
            'journalViews' => $generator->generateJournalBarChartData($slicedLastMonth),
            'articleViews' => $generator->generateArticleBarChartData($slicedLastMonth),
            'issueFileDownloads' => $generator->generateIssueFilePieChartData($slicedLastMonth),
            'articleFileDownloads' => $generator->generateArticleFilePieChartData($slicedLastMonth),
        ];

        $data = [
            'stats' => json_encode($json),
            'journals' => $generator->generateJournalViewsData(),
            'articles' => $generator->generateArticleViewsData(),
            'issueFiles' => $generator->generateIssueFileDownloadsData(),
            'articleFiles' => $generator->generateArticleFileDownloadsData(),
            'journalsMonthly' => $generator->generateJournalViewsData($slicedLastMonth),
            'articlesMonthly' => $generator->generateArticleViewsData($slicedLastMonth),
            'issueFilesMonthly' => $generator->generateIssueFileDownloadsData($slicedLastMonth),
            'articleFilesMonthly' => $generator->generateArticleFileDownloadsData($slicedLastMonth),
        ];

        $output->writeln('Removing cache for admin_statics');
        $cache->delete('admin_statics');

        $output->writeln('Saving cache for admin_statics');
        $cache->save('admin_statics', $data);

        $output->writeln('all done');
        return true;
    }
}
