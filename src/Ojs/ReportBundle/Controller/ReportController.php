<?php

namespace Ojs\ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReportController extends Controller
{

    public function indexAction()
    {
        return $this->render('OjsReportBundle::index.html.twig');
    }

}
