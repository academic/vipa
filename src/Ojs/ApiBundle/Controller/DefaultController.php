<?php

namespace Ojs\ApiBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $data['page'] = 'browse';

        return $this->render('OjsApiBundle::Default/index.html.twig', $data);
    }
}
