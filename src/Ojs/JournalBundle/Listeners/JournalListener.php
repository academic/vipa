<?php

namespace Ojs\JournalBundle\Listeners;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RequestContextAwareInterface;

class JournalListener implements EventSubscriberInterface
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var RequestContextAwareInterface
     */
    protected $router;

    /**
     * JournalListener constructor.
     * @param RequestStack $requestStack
     * @param RequestContextAwareInterface $router
     */
    public function __construct(RequestStack $requestStack, RequestContextAwareInterface $router)
    {
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 16)),
            KernelEvents::FINISH_REQUEST => array(array('onKernelFinishRequest', 0)),
        );
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $this->setRouterContext($request);
    }

    private function setRouterContext(Request $request)
    {
        $this->router->getContext()->setParameter('journalId', $request->attributes->get('journalId'));
    }

    public function onKernelFinishRequest()
    {
        if (null !== $parentRequest = $this->requestStack->getParentRequest()) {
            $this->setRouterContext($parentRequest);
        }
    }
}
