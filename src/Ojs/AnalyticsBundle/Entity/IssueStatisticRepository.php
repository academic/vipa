<?php

namespace Ojs\AnalyticsBundle\Entity;

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
    public function getByIssuesAndDates($issues, $dates)
    {
        $builder = $this->createQueryBuilder('stat');
        $builder
            ->andWhere('stat.issue IN (:issues)')
            ->andWhere('stat.date IN (:dates)')
            ->orderBy('stat.date', 'DESC')
            ->setParameters([
                'issues' => $issues,
                'dates' => $dates
            ]);

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
            ->join('OjsJournalBundle:Issue', 'issue', 'WHERE', 'issue = stat.issue')
            ->addSelect('SUM(stat.view) as totalViews')
            ->andWhere('stat.issue IN (:issues)')
            ->setParameter('issues', $issues)
            ->groupBy('stat.issue')
            ->orderBy('stat.view', 'DESC')
            ->setMaxResults($limit);

        return $builder->getQuery()->getResult();
    }
}
