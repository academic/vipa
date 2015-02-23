<?php
namespace Ojs\UserBundle\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Ojs\JournalBundle\Entity\Article;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ArticleListener
{
    protected $container;

    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Every article submission event, event log
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     * @link http://docs.doctrine-project.org/en/latest/reference/events.html#postupdate-postremove-postpersist
     * @return null
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        /* @var $user User */
        if(!$this->container->get('security.context')->getToken()){
		return false;
	}
	$user = $this->container->get('security.context')->getToken()->getUser();
	
        /**
         * perhaps you only want to act on some "Article" entity
         * @link http://docs.doctrine-project.org/en/latest/reference/events.html#listening-and-subscribing-to-lifecycle-events
         */
        if ($entity instanceof Article) {

            //log as eventlog
            $event = new \Ojs\UserBundle\Entity\EventLog();
            $event->setUserId($user->getId());
            $event->setEventInfo(\Ojs\Common\Params\ArticleEventLogParams::$ARTICLE_SUBMISSION);
            $event->setIp($this->container->get('request')->getClientIp());
            $entityManager->persist($event);

            $entityManager->flush();
        }
    }

    /**
     * Article remove event event log function.
     * @param LifecycleEventArgs $args
     * @return null
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        /* @var $user User */
        $user = $this->container->get('security.context')->getToken()->getUser();

        /**
         * perhaps you only want to act on some "Article" entity
         * @link http://docs.doctrine-project.org/en/latest/reference/events.html#listening-and-subscribing-to-lifecycle-events
         */
        if ($entity instanceof Article) {

            //log as eventlog
            $event = new \Ojs\UserBundle\Entity\EventLog();
            $event->setEventInfo(\Ojs\Common\Params\ArticleEventLogParams::$ARTICLE_REMOVE);
            $event->setIp($this->container->get('request')->getClientIp());
            $event->setUserId($user->getId());
            $entityManager->persist($event);

            $entityManager->flush();
        }
    }
}
