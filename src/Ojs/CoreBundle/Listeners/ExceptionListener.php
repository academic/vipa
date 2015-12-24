<?php

namespace Ojs\CoreBundle\Listeners;

use Ojs\CoreBundle\Exception\HasRelationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ExceptionListener
{
    /** @var RouterInterface */
    private $router;

    /** @var Session */
    private $session;

    /** @var TranslatorInterface */
    private $translator;

    /** @var  RequestStack */
    private $requestStack;

    /**
     * ExceptionListener constructor.
     * @param RouterInterface $router
     * @param Session $session
     * @param TranslatorInterface $translator
     */
    public function __construct(
        RouterInterface $router,
        Session $session,
        TranslatorInterface $translator,
        RequestStack $requestStack)
    {
        $this->router = $router;
        $this->session = $session;
        $this->translator = $translator;
        $this->requestStack = $requestStack;
    }


    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $request = $this->requestStack->getMasterRequest();
        $exception = $event->getException();
        if (!$exception instanceof HasRelationException) {
            return;
        }
        $errorText = $this->translator->trans(
            'deletion.remove_components_first',
            array('%field%' => $exception->getEntityName() . "\\" . $exception->getMapping()['fieldName'])
        );
        if($request->get('_format') == 'json'){
            $response = new JsonResponse(['error' => $errorText]);

            $event->setResponse($response);
            return;
        }
        $routeArr = $this->getRefererParams($event->getRequest());
        $route = $routeArr['_route'];
        unset($routeArr['_route']);
        unset($routeArr['__controller']);

        $this->session->getFlashBag()->add(
            'danger',
            $errorText
        );

        $url = $this->router->generate($route, $routeArr);

        $response = new RedirectResponse($url);

        $event->setResponse($response);
    }

    private function getRefererParams(Request $request)
    {
        $referer = $request->headers->get('referer');
        $matchBasePath = $this->router->match('');
        $baseUrl = $this->router->generate($matchBasePath['_route'], [], true);
        $lastPath = substr($referer, strpos($referer, $baseUrl) + strlen($baseUrl));
        return $this->router->match('/'.$lastPath);
    }
}
