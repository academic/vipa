<?php

namespace Ojs\NotifierBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OjsNotifierBundle:Default:index.html.twig', array('name' => $name));
    }
}
