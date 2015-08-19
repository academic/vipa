<?php

namespace Ojs\AnalyticsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Ojs\JournalBundle\Entity\ArticleFile;

class ArticleFileStatisticRepository extends EntityRepository
{
    /**
     * Gets statistics of given article files on given dates
     *
     * @param ArticleFile $articleFile
     * @param array $dates
     * @return ArrayCollection
     */
    public function getTotalDownloadsByDates($articleFile, $dates)
    {
        $builder = $this->createQueryBuilder('stat');
        $builder
            ->join('OjsJournalBundle:ArticleFile', 'file', 'WHERE', 'stat.articleFile = file')
            ->addSelect('SUM(stat.download)')
            ->andWhere('stat.date IN (:dates)')
            ->andWhere('file = :file')
            ->groupBy('file')
            ->setParameters([
                'file'  => $articleFile,
                'dates' => $dates
            ]);

        return $builder->getQuery()->getResult();
    }

    public function getTotalDownloadsOfAllFiles($article, $dates)
    {
        $builder = $this->createQueryBuilder('stat');
        $builder
            ->join('OjsJournalBundle:ArticleFile', 'file', 'WHERE', 'stat.articleFile = file')
            ->join('OjsJournalBundle:Article', 'article', 'WHERE', 'file.article = article')
            ->addSelect('SUM(stat.download)')
            ->andWhere('article = :article')
            ->andWhere('stat.date IN (:dates)')
            ->groupBy('article')
            ->setParameters([
                'article'  => $article,
                'dates'    => $dates
            ]);

        return $builder->getQuery()->getResult();
    }
}
