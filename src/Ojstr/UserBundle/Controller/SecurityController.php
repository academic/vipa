<?php

namespace Ojstr\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller {

    /**
     * @Route("/login")
     * @Method({"GET"})
     */
    public function postLogin() {
        return new Response("login with callback " . $callback_uri);
    }

    /**
     * @Route("/check_login")
     * @Method({"GET"})
     */
    public function getCheckLogin($callback_uri = NULL) {
        return new Response("check login with callback " . $callback_uri);
    }

    /**
     * @Route("/logout")
     * @Method({"GET"})
     */
    public function getLogout($callback_uri = NULL) {
        return new Response("logout with callback " . $callback_uri);
    }

}
