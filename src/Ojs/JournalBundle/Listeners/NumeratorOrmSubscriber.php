<?php

namespace Ojs\JournalBundle\Listeners;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\NoResultException;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Numerator;
use Ojs\JournalBundle\Helper\NumeratorHelper;

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
        }
    }
}
