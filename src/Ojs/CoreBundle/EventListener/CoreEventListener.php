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
        $adminUsers = $this->getAdminUsers();

        foreach ($adminUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Core Event : Core Install Base',
                'Core Event : Core Install Base'
            );
        }
    }

    /**
     * @return \Doctrine\Common\Collections\Collection | User[]
     * @link http://stackoverflow.com/a/16692911
     */
    private function getAdminUsers()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('u')
            ->from('OjsUserBundle:User', 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%ROLE_SUPER_ADMIN%');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param CoreEvent $event
     */
    public function onInstall3Party(CoreEvent $event)
    {
        $adminUsers = $this->getAdminUsers();

        foreach ($adminUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Core Event : Core Install 3 Party',
                'Core Event : Core Install 3 Party'
            );
        }
    }
}
