<?php

namespace Vipa\AnalyticsBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Vipa\JournalBundle\Entity\IssueFile;

class IssueFileStatisticRepository extends EntityRepository
{
    /**
     * Returns the download count of the given issue file on given dates
     *
     * @param IssueFile $issueFile
     * @param array $dates
     * @return array
     */
    public function getTotalDownloads($issueFile, $dates)
    {
        $builder = $this->createQueryBuilder('stat');
        $builder
            ->join('VipaJournalBundle:IssueFile', 'file', 'WHERE', 'stat.issueFile = file')
            ->addSelect('SUM(stat.download)')
            ->andWhere('stat.date IN (:dates)')
            ->andWhere('file = :file')
            ->groupBy('file')
            ->setParameters([
                'file'  => $issueFile,
                'dates' => $dates
            ]);

        return $builder->getQuery()->getResult();
    }

    /**
     * Returns the download count of the given issue's files on given dates
     *
     * @param $issues
     * @param $dates
     * @return array
     */
    public function getTotalDownloadsOfAllFiles($issues, $dates = null)
    {
        $builder = $this->createQueryBuilder('stat');

        if ($dates !== null) {
            $builder
                ->andWhere('stat.date IN (:dates)')
                ->setParameter('dates', $dates);
        }

        $builder
            ->join('VipaJournalBundle:IssueFile', 'file', 'WHERE', 'stat.issueFile = file')
            ->join('VipaJournalBundle:Issue', 'issue', 'WHERE', 'file.issue IN (:issues)')
            ->addSelect('SUM(stat.download)')
            ->andWhere('issue IN (:issues)')
            ->groupBy('issue, stat.id')
            ->setParameter('issues', $issues);

        return $builder->getQuery()->getResult();
    }

    public function getMostDownloadedFiles($issues, $dates = null, $limit = null)
    {
        $builder = $this->createQueryBuilder('stat');

        if ($dates !== null) {
            $builder
                ->andWhere('stat.date IN (:dates)')
                ->setParameter('dates', $dates);
        }

        $builder
            ->join('VipaJournalBundle:IssueFile', 'file', 'WHERE', 'stat.issueFile = file')
            ->join('VipaJournalBundle:Issue', 'issue', 'WHERE', 'file.issue = issue')
            ->addSelect('SUM(stat.download)')
            ->andWhere('issue IN (:issues)')
            ->groupBy('stat.issueFile, stat.id')
            ->orderBy('stat.download', 'DESC')
            ->setMaxResults($limit)
            ->setParameter('issues', $issues);

        return $builder->getQuery()->getResult();
    }
}
