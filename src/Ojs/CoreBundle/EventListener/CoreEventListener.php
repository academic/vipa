<?php

namespace Ojs\CoreBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Ojs\CoreBundle\Events\CoreEvent;
use Ojs\CoreBundle\Events\CoreEvents;
use Ojs\CoreBundle\Service\OjsMailer;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class CoreEventListener implements EventSubscriberInterface
{
    /** @var RouterInterface */
    private $router;

    /** @var EntityManager */
    private $em;

    /** @var OjsMailer */
    private $ojsMailer;

    /**
     * @param RouterInterface $router
     * @param EntityManager $em
     * @param OjsMailer $ojsMailer
     *
     */
    public function __construct(
        RouterInterface $router,
        EntityManager $em,
        OjsMailer $ojsMailer
    ) {
        $this->router = $router;
        $this->em = $em;
        $this->ojsMailer = $ojsMailer;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            CoreEvents::OJS_INSTALL_BASE => 'onInstallBase',
            CoreEvents::OJS_INSTALL_3PARTY => 'onInstall3Party',
        );
    }

    /**
     * @param CoreEvent $event
     */
    public function onInstallBase(CoreEvent $event)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(CoreEvents::OJS_INSTALL_BASE);
        foreach ($this->ojsMailer->getAdminUsers() as $user) {
            $transformParams = [
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
            ];
            $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->ojsMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }
    }

    /**
     * @param CoreEvent $event
     */
    public function onInstall3Party(CoreEvent $event)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(CoreEvents::OJS_INSTALL_BASE);
        foreach ($this->ojsMailer->getAdminUsers() as $user) {
            $transformParams = [
                'bundleName'        => $event->getBundleName(),
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
            ];
            $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->ojsMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }
    }
}
