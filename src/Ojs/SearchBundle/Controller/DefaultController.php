<?php

namespace Ojs\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OjsSearchBundle:Default:index.html.twig');
    }

}
