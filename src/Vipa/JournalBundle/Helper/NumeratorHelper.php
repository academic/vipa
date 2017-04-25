<?php

namespace Vipa\JournalBundle\Helper;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NoResultException;
use Vipa\JournalBundle\Entity\Article;
use Vipa\JournalBundle\Entity\Issue;
use Vipa\JournalBundle\Entity\Numerator;

class NumeratorHelper
{
    public static function numerateArticle(Article $article, ObjectManager $entityManager)
    {
        $journal = $article->getJournal();

        if ($article->getNumerator() === null) {
            try {
                $numerator = $entityManager
                    ->getRepository('VipaJournalBundle:Numerator')
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
    
    public static function numerateIssue(Issue $issue, ObjectManager $entityManager)
    {
        $journal = $issue->getJournal();

        if ($issue->getNumerator() === null) {
            try {
                $numerator = $entityManager
                    ->getRepository('VipaJournalBundle:Numerator')
                    ->getIssueNumerator($journal);
                $last = $numerator->getLast() + 1;
                $numerator->setLast($last);
                $issue->setNumerator($last);
            } catch (NoResultException $exception) {
                $numerator = new Numerator();
                $numerator->setJournal($journal);
                $numerator->setType('issue');
                $numerator->setLast(1);
                $issue->setNumerator(1);
            }

            $entityManager->persist($issue);
            $entityManager->persist($numerator);
            $entityManager->flush();
        }
    }
}
