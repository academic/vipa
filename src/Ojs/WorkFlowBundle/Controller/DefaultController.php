<?php

namespace Ojs\WorkFlowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OjsWorkFlowBundle:Default:index.html.twig', array('name' => $name));
    }
}
