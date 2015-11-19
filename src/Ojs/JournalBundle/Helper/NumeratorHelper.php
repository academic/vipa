<?php

namespace Ojs\JournalBundle\Helper;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NoResultException;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Numerator;

class NumeratorHelper
{
    public static function numerateArticle(Article $article, ObjectManager $entityManager)
    {
        $journal = $article->getJournal();

        if ($article->getNumerator() === null) {
            try {
                $numerator = $entityManager
                    ->getRepository('OjsJournalBundle:Numerator')
                    ->getArticleNumerator($journal);
                $last = $numerator->getLast() + 1;
                $numerator->setLast($last);
                $article->setNumerator($last);
            } catch (NoResultException $exception) {
                $numerator = new Numerator();
                $numerator->setJournal($journal);
                $numerator->setType('article');
                $numerator->setLast(1);
                $article->setNumerator(1);
            }

            $entityManager->persist($article);
            $entityManager->persist($numerator);
            $entityManager->flush();
        }
    }
}
