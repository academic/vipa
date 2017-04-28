<?php

namespace Vipa\JournalBundle\Listeners;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\NoResultException;
use Vipa\JournalBundle\Entity\Article;
use Vipa\JournalBundle\Entity\Issue;
use Vipa\JournalBundle\Entity\Numerator;
use Vipa\JournalBundle\Helper\NumeratorHelper;

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
            NumeratorHelper::numerateArticle($entity, $entityManager);
        } else if ($entity instanceof Issue) {
            NumeratorHelper::numerateIssue($entity, $entityManager);
        }
    }
}
