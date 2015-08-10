<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class ArticleRepository
 * @package Ojs\JournalBundle\Entity
 */
class ArticleRepository extends EntityRepository
{
    /**
     * Get articles that has no issue_id
     * @param  int $status
     * @return Article[]
     */
    public function getArticlesUnissued($status = 1)
    {
        $q = $this->createQueryBuilder('a')
            ->select('a')
            ->where('a.issue IS NULL AND a.status = :status')
            ->setParameter('status', $status)
            ->getQuery();
        $articles = $q->getResult();

        return $articles;
    }

    /**
     * Get article list by issue_id with orderNum attribute ordered
     * @param  Issue $issue
     * @param  bool $asc
     * @param  int $status default 3 (published)  see Ojs\Common\CommonParams
     * @return Article[]
     */
    public function getOrderedArticlesByIssue(Issue $issue, $asc = false, $status = 3)
    {
        $q = $this->createQueryBuilder('a')
            ->select('a')
            ->where('a.issue = :issue AND a.status = :status')
            ->orderBy('a.orderNum', $asc ? 'ASC' : 'DESC')
            ->setParameter('issue', $issue)
            ->setParameter('status', $status)
            ->getQuery();

        $articles = $q->getResult();

        return $articles;
    }

    /**
     * @param $page
     * @param $limit
     * @return array
     */
    public function findAllByLimits($page, $limit)
    {
        $result = $this->createQueryBuilder('a')
            ->setFirstResult($page)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
        return $result;
    }

    /**
     * Return article count by condition
     * @param $field
     * @param $value
     * @return mixed
     */
    public function getCountBy($field, $value)
    {
        $qb = $this->createQueryBuilder("a");
        $qb->select("count(a.id)")
            ->where(
                $qb->expr()->eq("a.$field", ':value')
            )
            ->setParameter("value", $value);
        return $qb->getQuery()->getSingleScalarResult();
    }
}
