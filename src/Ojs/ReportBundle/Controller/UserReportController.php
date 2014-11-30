<?php

namespace Ojs\ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserReportController extends Controller
{

    public function indexAction()
    {
        return $this->render('OjsReportBundle:user:index.html.twig');
    }

}
