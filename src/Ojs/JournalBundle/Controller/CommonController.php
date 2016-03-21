<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Common controller.
 *
 */
class CommonController extends Controller
{
    public function changeLocaleAction($code, Request $request)
    {
        $request->setLocale($code);
        $this->get('session')->set('_locale', $code);
        $referer = $request->headers->get('referer');

        return $this->redirect(empty($referer) ? "/" : $referer);
    }
}
