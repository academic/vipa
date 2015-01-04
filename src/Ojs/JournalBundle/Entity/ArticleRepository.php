<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{

    /**
     * Get Article full text file. 
     * @param  integer $articleId
     * @return ArticleFile
     * @throws NoResultException
     */
    public function getArticleFullTextFile($articleId)
    {
        $q = $this
                ->createQueryBuilder('a')
                ->select('a, f')
                ->leftJoin('a.articleFiles', 'f')
                ->where('a.article_id = :article_id AND f.type= 0')
                ->setParameter('article_id', $articleId)
                ->getQuery();
        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no record matching the criteria.
            $article = $q->getSingleResult();
            return $article;
        } catch (NoResultException $e) {
            $message = sprintf('There is no full text file for this article.');
            throw new UsernameNotFoundException($message, 0, $e);
        }
    }

    /**
     * Get articles that has no issue_id
     * @param integer $status
     * @return type
     */
    public function getArticlesUnissued($status = 3)
    {
        $q = $this->createQueryBuilder('a')
                ->select('a')
                ->where('a.issueId IS NULL AND a.status = :status')
                ->setParameter('status', $status)
                ->getQuery();
        try {
            $articles = $q->getResult();
            return $articles;
        } catch (\Exception $e) {
            $logger = $this->get('logger');
            $logger->error($e->getMessage());
        }
        return array();
    }

    /**
     * Get article list by issue_id with orderNum attribute ordered 
     * @param \Ojs\JournalBundle\Entity\Issue $issue
     * @param string $asc
     * @param int $status  default 3 (published)  see Ojs\Common\CommongParams
     * @return array
     * @throws \Exception
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
        try {
            $articles = $q->getResult();
            return $articles;
        } catch (\Exception $e) {
            $logger = $this->get('logger');
            $logger->error($e->getMessage());
        }
        return array();
    }

}
