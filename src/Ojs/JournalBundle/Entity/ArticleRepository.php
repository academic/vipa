<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Ojs\CoreBundle\Params\ArticleStatuses;

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
    public function getArticlesUnissued($status = ArticleStatuses::STATUS_PUBLISHED)
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
     * @param  int $status default 1 (published)  see Ojs\JournalBundle\Entity\Article
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
     * Get article list by section_id with orderNum attribute ordered
     * @param  Section $section
     * @param  bool $asc
     * @param  int $status default 1 (published)  see Ojs\JournalBundle\Entity\Article
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
