<?php

namespace Ojs\JournalBundle\Listeners;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\NoResultException;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Numerator;

class NumeratorOrmSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(Events::postPersist);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();

        if ($entity instanceof Article) {
            $journal = $entity->getJournal();
            try {
                $numerator = $entityManager
                    ->getRepository('OjsJournalBundle:Numerator')
                    ->getArticleNumerator($journal);
                $last = $numerator->getLast() + 1;
                $numerator->setLast($last);
                $entity->setNumerator($last);
            } catch (NoResultException $exception) {
                $numerator = new Numerator();
                $numerator->setJournal($journal);
                $numerator->setType('article');
                $numerator->setLast(1);
                $entity->setNumerator(1);
            }

            $entityManager->persist($entity);
            $entityManager->persist($numerator);
            $entityManager->flush();
        }
    }
}