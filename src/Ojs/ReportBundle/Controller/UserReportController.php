<?php

namespace Ojs\ReportBundle\Controller;

 use Ojs\Common\Controller\OjsController as Controller;

class UserReportController extends Controller
{

    public function indexAction()
    {
        //$em = $this->getDoctrine()->getManager();

        return $this->render('OjsReportBundle:user:index.html.twig');
    }
}
