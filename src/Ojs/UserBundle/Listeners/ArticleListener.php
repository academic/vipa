<?php
namespace Ojs\UserBundle\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Ojs\JournalBundle\Entity\Article;
use Ojs\UserBundle\Entity\EventLog;
use Ojs\UserBundle\Entity\User;
use Ojs\Common\Params\ArticleEventLogParams;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ArticleListener
{
    /** @var TokenStorage  */
    protected $tokenStorage;
    /** @var RequestStack  */
    protected $request;

    /**
     * @param TokenStorage $tokenStorage
     * @param RequestStack      $request
     */
    public function __construct(TokenStorage $tokenStorage, RequestStack $request)
    {
        $this->tokenStorage = $tokenStorage;
        $this->request = $request;
    }

    /**
     * Every article submission event, event log
     * @param  LifecycleEventArgs $args
     * @link http://docs.doctrine-project.org/en/latest/reference/events.html#postupdate-postremove-postpersist
     * @return null
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        if (php_sapi_name() != 'cli') {
            $entity = $args->getEntity();
            $entityManager = $args->getEntityManager();

            $token = $this->tokenStorage->getToken();
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
                $event = new EventLog();
                $event->setUserId($user->getId());
                $event->setEventInfo(ArticleEventLogParams::$ARTICLE_SUBMISSION);
                $event->setIp($this->request->getCurrentRequest()->getClientIp());
                $entityManager->persist($event);

                $entityManager->flush();
            }
        }
    }

    /**
     * Article remove event event log function.
     * @param  LifecycleEventArgs $args
     * @return null
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        if (php_sapi_name() != 'cli') {
            $entity = $args->getEntity();
            $entityManager = $args->getEntityManager();

            /* @var $user User */
            $user = $this->tokenStorage->getToken()->getUser();

            /**
             * perhaps you only want to act on some "Article" entity
             * @link http://docs.doctrine-project.org/en/latest/reference/events.html#listening-and-subscribing-to-lifecycle-events
             */
            if ($entity instanceof Article) {

                //log as eventlog
                $event = new EventLog();
                $event->setEventInfo(ArticleEventLogParams::$ARTICLE_REMOVE);
                $event->setIp($this->request->getCurrentRequest()->getClientIp());
                $event->setUserId($user->getId());
                $event->setAffectedUserId($entity->getSubmitterId());
                $entityManager->persist($event);

                $entityManager->flush();
            }
        }
    }
}
