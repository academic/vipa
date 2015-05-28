<?php

namespace Ojs\WorkflowBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OjsWorkflowBundle:Default:index.html.twig');
    }
}
