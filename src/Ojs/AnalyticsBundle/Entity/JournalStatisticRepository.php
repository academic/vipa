<?php

namespace Ojs\AnalyticsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Ojs\JournalBundle\Entity\Journal;

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

        return $builder->getQuery()->getResult();
    }

    /**
     * Gets statistics of most viewed amongst given journals
     *
     * @param array|Journal $journals
     * @param array $dates
     * @param int $limit
     * @return ArrayCollection
     */
    public function getTotalViewCounts($journals, $dates = null, $limit = null)
    {
        $builder = $this->createQueryBuilder('stat');

        if ($dates !== null) {
            $builder
                ->andWhere('stat.date IN (:dates)')
                ->setParameter('dates', $dates);
        }

        $builder
            ->join('OjsJournalBundle:Journal', 'journal', 'WHERE', 'journal = stat.journal')
            ->addSelect('SUM(stat.view)')
            ->andWhere('stat.journal IN (:journals)')
            ->setParameter('journals', $journals)
            ->groupBy('stat.journal')
            ->orderBy('stat.view', 'DESC')
            ->setMaxResults($limit);

        return $builder->getQuery()->getResult();
    }
}
