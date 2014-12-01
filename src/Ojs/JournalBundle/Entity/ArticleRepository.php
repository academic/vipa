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

}
