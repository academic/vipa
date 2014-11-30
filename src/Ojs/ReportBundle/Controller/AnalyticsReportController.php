<?php

namespace Ojs\ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AnalyticsReportController extends Controller
{

    public function indexAction()
    {
        return $this->render('OjsReportBundle:analytics:index.html.twig');
    }

}
