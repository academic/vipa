<?php

namespace Vipa\JournalBundle\Controller;

use Vipa\CoreBundle\Controller\VipaController as Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Common controller.
 *
 */
class CommonController extends Controller
{
    /**
     * @param $code
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeLocaleAction($code, Request $request)
    {
        $session = $this->get('session');
        $request->setLocale($code);
        $session->set('_locale', $code);
        $session->set('_locale_prefered', new \DateTime());
        $referer = $request->headers->get('referer');

        return $this->redirect(empty($referer) ? "/" : $referer);
    }
}
