<?php

namespace Vipa\AnalyticsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class IssueStatisticRepository extends EntityRepository
{
    /**
     * Gets statistics of given issues on given dates
     *
     * @param array $issues
     * @param array $dates
     * @return ArrayCollection
     */
    public function findByIssues($issues, $dates = null)
    {
        $builder = $this->createQueryBuilder('stat');

        if ($dates !== null) {
            $builder
                ->andWhere('stat.date IN (:dates)')
                ->setParameter('dates', $dates);
        }

        $builder
            ->andWhere('stat.issue IN (:issues)')
            ->orderBy('stat.date', 'DESC')
            ->setParameter('issues', $issues);

        return $builder->getQuery()->getResult();
    }

    /**
     * Gets statistics of most viewed amongst given issues
     *
     * @param array $issues
     * @param array $dates
     * @param int $limit
     * @return ArrayCollection
     */
    public function getMostViewed($issues, $dates = null, $limit = null)
    {
        $builder = $this->createQueryBuilder('stat');

        if ($dates !== null) {
            $builder
                ->andWhere('stat.date IN (:dates)')
                ->setParameter('dates', $dates);
        }

        $builder
            ->join('VipaJournalBundle:Issue', 'issue', 'WHERE', 'issue = stat.issue')
            ->addSelect('SUM(stat.view) as totalViews')
            ->andWhere('stat.issue IN (:issues)')
            ->setParameter('issues', $issues)
            ->groupBy('stat.issue, stat.id')
            ->orderBy('stat.view', 'DESC')
            ->setMaxResults($limit);

        return $builder->getQuery()->getResult();
    }
}
