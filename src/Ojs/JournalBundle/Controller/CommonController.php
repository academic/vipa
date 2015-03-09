<?php

namespace Ojs\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;

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
