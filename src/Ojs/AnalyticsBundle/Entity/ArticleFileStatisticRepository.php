<?php

namespace Ojs\AnalyticsBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Ojs\JournalBundle\Entity\ArticleFile;

class ArticleFileStatisticRepository extends EntityRepository
{
    /**
     * Returns the download count of the given article file on given dates
     *
     * @param ArticleFile $articleFile
     * @param array $dates
     * @return array
     */
    public function getTotalDownloads($articleFile, $dates)
    {
        $builder = $this->createQueryBuilder('stat');
        $builder
            ->join('OjsJournalBundle:ArticleFile', 'file', 'WHERE', 'stat.articleFile = file')
            ->addSelect('SUM(stat.download)')
            ->andWhere('stat.date IN (:dates)')
            ->andWhere('file = :file')
            ->groupBy('stat.id, file')
            ->setParameters(
                [
                    'file' => $articleFile,
                    'dates' => $dates
                ]
            );

        return $builder->getQuery()->getResult();
    }

    /**
     * Returns the download count of the given article's files on given dates
     *
     * @param $articles
     * @param $dates
     * @return array
     */
    public function getTotalDownloadsOfAllFiles($articles, $dates = null)
    {
        $builder = $this->createQueryBuilder('stat');

        if ($dates !== null) {
            $builder
                ->andWhere('stat.date IN (:dates)')
                ->setParameter('dates', $dates);
        }

        $builder
            ->join('OjsJournalBundle:ArticleFile', 'file', 'WHERE', 'stat.articleFile = file')
            ->join('OjsJournalBundle:Article', 'article', 'WHERE', 'file.article IN (:articles)')
            ->addSelect('SUM(stat.download)')
            ->andWhere('article IN (:articles)')
            ->groupBy('article, stat.id, file')
            ->setParameter('articles', $articles);

        return $builder->getQuery()->getResult();
    }

    /**
     * @param $articles
     * @param null $dates
     * @param null $limit
     * @return array
     */
    public function getMostDownloadedFiles($articles, $dates = null, $limit = null)
    {
        $builder = $this->createQueryBuilder('stat');

        if ($dates !== null) {
            $builder
                ->andWhere('stat.date IN (:dates)')
                ->setParameter('dates', $dates);
        }

        $builder
            ->join('OjsJournalBundle:ArticleFile', 'file', 'WHERE', 'stat.articleFile = file')
            ->join('OjsJournalBundle:Article', 'article', 'WHERE', 'file.article = article')
            ->addSelect('SUM(stat.download)')
            ->andWhere('article IN (:articles)')
            ->groupBy('stat.articleFile, stat.id')
            ->orderBy('stat.download', 'DESC')
            ->setMaxResults($limit)
            ->setParameter('articles', $articles);

        return $builder->getQuery()->getResult();
    }
}
