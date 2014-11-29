<?php

namespace Ojs\ApiBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    /**
     *
     */
    public function indexAction() {
        $data['page'] = 'browse';
        return $this->render('OjsApiBundle::Default/index.html.twig', $data);
    }

}
