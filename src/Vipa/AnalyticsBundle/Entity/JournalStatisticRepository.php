<?php

namespace Vipa\AnalyticsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Vipa\JournalBundle\Entity\Journal;

class JournalStatisticRepository extends EntityRepository
{
    /**
     * Gets statistics of given journals on given dates
     *
     * @param array $journals
     * @param array $dates
     * @return ArrayCollection
     */
    public function findByJournals($journals, $dates = null)
    {
        $builder = $this->createQueryBuilder('stat');

        if ($dates !== null) {
            $builder
                ->andWhere('stat.date IN (:dates)')
                ->setParameter('dates', $dates);
        }

        $builder
            ->andWhere('stat.journal IN (:journals)')
            ->orderBy('stat.date', 'DESC')
            ->setParameter('journals', $journals);

        return new ArrayCollection($builder->getQuery()->getResult());
    }

    /**
     * Gets statistics of most viewed amongst given journals
     *
     * @param array|Journal $journals
     * @param array $dates
     * @param int $limit
     * @return ArrayCollection
     */
    public function getMostViewed($journals, $dates = null, $limit = null)
    {
        $builder = $this->createQueryBuilder('stat');

        if ($dates !== null) {
            $builder
                ->andWhere('stat.date IN (:dates)')
                ->setParameter('dates', $dates);
        }

        $builder
            ->join('VipaJournalBundle:Journal', 'journal', 'WHERE', 'journal = stat.journal')
            ->addSelect('SUM(stat.view)')
            ->andWhere('stat.journal IN (:journals)')
            ->setParameter('journals', $journals)
            ->groupBy('stat.journal, stat.id')
            ->orderBy('stat.view', 'DESC')
            ->setMaxResults($limit);

        return new ArrayCollection($builder->getQuery()->getResult());
    }
}
