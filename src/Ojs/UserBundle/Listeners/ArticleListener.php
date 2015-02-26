<?php
namespace Ojs\UserBundle\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Ojs\JournalBundle\Entity\Article;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use \Ojs\Common\Params\ArticleEventLogParams;

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
        if (php_sapi_name()!='cli') {
            $entity = $args->getEntity();
            $entityManager = $args->getEntityManager();

            $token = $this->container->get('security.context')->getToken();
            if (!$token) {
                $user = $entityManager->getReference('OjsUserBundle:User', 1);
            } else {
                /* @var $user User */
                $user = $token->getUser();
            }

            /**
             * perhaps you only want to act on some "Article" entity
             * @link http://docs.doctrine-project.org/en/latest/reference/events.html#listening-and-subscribing-to-lifecycle-events
             */
            if ($entity instanceof Article) {

                //log as eventlog
                $event = new \Ojs\UserBundle\Entity\EventLog();
                $event->setUserId($user->getId());
                $event->setEventInfo(ArticleEventLogParams::$ARTICLE_SUBMISSION);
                $event->setIp($this->container->get('request')->getClientIp());
                $entityManager->persist($event);

                $entityManager->flush();
            }
        }
    }

    /**
     * Article remove event event log function.
     * @param LifecycleEventArgs $args
     * @return null
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        if (php_sapi_name()!='cli') {
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
                $event->setEventInfo(ArticleEventLogParams::$ARTICLE_REMOVE);
                $event->setIp($this->container->get('request')->getClientIp());
                $event->setUserId($user->getId());
                $event->setAffectedUserId($entity->getSubmitterId());
                $entityManager->persist($event);

                $entityManager->flush();
            }
        }
    }
}