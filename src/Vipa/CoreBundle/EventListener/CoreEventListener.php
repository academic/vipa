<?php

namespace Vipa\CoreBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Vipa\CoreBundle\Events\CoreEvent;
use Vipa\CoreBundle\Events\CoreEvents;
use Vipa\CoreBundle\Service\Mailer;
use Vipa\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class CoreEventListener implements EventSubscriberInterface
{
    /** @var RouterInterface */
    private $router;

    /** @var EntityManager */
    private $em;

    /** @var Mailer */
    private $vipaMailer;

    /**
     * @param RouterInterface $router
     * @param EntityManager $em
     * @param Mailer $vipaMailer

     */
    public function __construct(
        RouterInterface $router,
        EntityManager $em,
        Mailer $vipaMailer
    ) {
        $this->router = $router;
        $this->em = $em;
        $this->vipaMailer = $vipaMailer;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            CoreEvents::VIPA_INSTALL_3PARTY => 'onInstall3Party',
        );
    }

    /**
     * @param CoreEvent $event
     */
    public function onInstall3Party(CoreEvent $event)
    {
        $getMailEvent = $this->vipaMailer->getTemplateByEvent(CoreEvents::VIPA_INSTALL_BASE);
        if(!$getMailEvent){
            return;
        }
        foreach ($this->vipaMailer->getAdmins() as $user) {
            $transformParams = [
                'bundleName'        => $event->getBundleName(),
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
            ];
            $template = $this->vipaMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->vipaMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }
    }
}
