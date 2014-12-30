<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{

    /**
     * @param  integer $articleId
     * @return ArticleFile
     * @throws UsernameNotFoundException
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
     * Get article list by issue_id with orderNum attribute ordered 
     * @param \Ojs\JournalBundle\Entity\Issue $issue
     */
    public function getOrderedArticlesByIssue(Issue $issue, $asc = false, $status = 1)
    {
        $q = $this->createQuery('SELECT a FROM OjsJournalBundle:Article a WHERE issueId = ?1 '
                        . ' ORDER BY orderNum ?2')
                ->setParameter(1, $issue->getId())
                ->setParameter(2, ($asc ? 'ASC' : 'DESC'))
                ->getQuery();
        try {
            $articles = $q->getResult();
            return $articles;
        } catch (NoResultException $e) {
            return false;
        }
        return false;
    }

}
