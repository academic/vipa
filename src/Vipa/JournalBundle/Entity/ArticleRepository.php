<?php

namespace Vipa\JournalBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Vipa\CoreBundle\Params\ArticleStatuses;

/**
 * Class ArticleRepository
 * @package Vipa\JournalBundle\Entity
 */
class ArticleRepository extends EntityRepository
{
    /**
     * Get articles that has no issue_id
     * @param  array $statuses
     * @return Article[]
     */
    public function getArticlesUnissued($statuses = [
        ArticleStatuses::STATUS_PUBLISHED,
        ArticleStatuses::STATUS_PUBLISH_READY,
        ArticleStatuses::STATUS_EARLY_PREVIEW,
    ])
    {
        $statusBag = [];
        foreach($statuses as $status){
            $statusBag[] = 'a.status = '.$status;
        }
        $statusDql = '('.implode(' OR ', $statusBag).')';
        $q = $this->createQueryBuilder('a')
            ->select('a')
            ->where('a.issue IS NULL AND '.$statusDql)
            ->getQuery();
        $articles = $q->getResult();

        return $articles;
    }

    /**
     * Get articles of given issue, ordered by their orderNum fields
     * @param Issue $issue
     * @param bool $asc
     * @param int $status
     * @return Article[]
     */
    public function getOrderedArticlesByIssue(Issue $issue, $asc = false, $status = ArticleStatuses::STATUS_PUBLISHED)
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
     * Get articles of given issue and section, ordered by their orderNum fields
     * @param Issue $issue
     * @param Section $section
     * @param bool $asc
     * @param int $status
     * @return Article[]
     */
    public function getOrderedArticles(Issue $issue, Section $section, $asc = true, $status = ArticleStatuses::STATUS_PUBLISHED)
    {
        $query = $this->createQueryBuilder('article')
            ->select('article')
            ->where('article.section = :section')
            ->andWhere('article.issue = :issue')
            ->andWhere('article.status = :status')
            ->orderBy('article.orderNum', $asc ? 'ASC' : 'DESC')
            ->setParameter('section', $section)
            ->setParameter('issue', $issue)
            ->setParameter('status', $status)
            ->getQuery();

        $articles = $query->getResult();

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
     * @param string $doi
     * @return array
     */
    public function findByDoi(string $doi)
    {
        $builder = $this
            ->createQueryBuilder("a")
            ->where("a.issue IS NOT NULL")
            ->andWhere("a.doi = :doi")
            ->setParameter("doi", $doi);
        return $builder->getQuery()->getResult();
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

    public function getIds()
    {
        $query = $this
            ->createQueryBuilder('article')
            ->select('article.id')
            ->getQuery();

        return $query->getArrayResult();
    }
}
