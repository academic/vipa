<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{

    /**
     * Get articles that has no issue_id
     * @param  int       $status
     * @return Article[]
     */
    public function getArticlesUnissued($status = 3)
    {
        $q = $this->createQueryBuilder('a')
            ->select('a')
            ->where('a.issueId IS NULL AND a.status = :status')
            ->setParameter('status', $status)
            ->getQuery();
        $articles = $q->getResult();

        return $articles;
    }

    /**
     * Get article list by issue_id with orderNum attribute ordered
     * @param  Issue     $issue
     * @param  bool      $asc
     * @param  int       $status default 3 (published)  see Ojs\Common\CommonParams
     * @return Article[]
     */
    public function getOrderedArticlesByIssue(Issue $issue, $asc = false, $status = 3)
    {
        $q = $this->createQueryBuilder('a')
            ->select('a')
            ->where('a.issueId = :issue_id AND a.status = :status')
            ->orderBy('a.orderNum', $asc ? 'ASC' : 'DESC')
            ->setParameter('issue_id', $issue->getId())
            ->setParameter('status', $status)
            ->getQuery();

        $articles = $q->getResult();

        return $articles;
    }
}
