<?php

namespace Ojstr\WorkflowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OjstrWorkflowBundle:Default:index.html.twig', array('name' => $name));
    }
}
