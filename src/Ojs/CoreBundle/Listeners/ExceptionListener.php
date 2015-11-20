<?php

namespace Ojs\CoreBundle\Listeners;

use Ojs\CoreBundle\Exception\ChildNotEmptyException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ExceptionListener
{
    /** @var RouterInterface */
    private $router;

    /** @var Session */
    private $session;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * ExceptionListener constructor.
     * @param RouterInterface $router
     * @param Session $session
     * @param TranslatorInterface $translator
     */
    public function __construct(RouterInterface $router, Session $session, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->session = $session;
        $this->translator = $translator;
    }


    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if (!$exception instanceof ChildNotEmptyException) {
            return;
        }
        $routeArr = $this->getRefererParams($event->getRequest());
        $route = $routeArr['_route'];
        unset($routeArr['_route']);
        unset($routeArr['__controller']);

        $this->session->getFlashBag()->add(
            'danger',
            $this->translator->trans(
                'firstly.remove.components',
                array('%field%' => $exception->getMapping()['fieldName'])
            )
        );

        $url = $this->router->generate($route, $routeArr);

        $response = new RedirectResponse($url);

        $event->setResponse($response);
    }

    private function getRefererParams(Request $request)
    {
        $referer = $request->headers->get('referer');
        $baseUrl = $request->getBaseUrl();
        $lastPath = substr($referer, strpos($referer, $baseUrl) + strlen($baseUrl));

        return $this->router->match($lastPath);
    }
}
