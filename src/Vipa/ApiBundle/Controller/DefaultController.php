<?php

namespace Vipa\ApiBundle\Controller;

use Vipa\CoreBundle\Controller\VipaController as Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $data['page'] = 'browse';

        return $this->render('VipaApiBundle::Default/index.html.twig', $data);
    }
}
