<?php

namespace Ojs\WorkflowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OjsWorkflowBundle:Default:index.html.twig');
    }

}
