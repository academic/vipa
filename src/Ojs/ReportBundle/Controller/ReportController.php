<?php

namespace Ojs\ReportBundle\Controller;

 use Ojs\Common\Controller\OjsController as Controller;

class ReportController extends Controller
{

    public function indexAction()
    {
        return $this->render('OjsReportBundle::index.html.twig');
    }
}
