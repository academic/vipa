<?php

namespace Ojs\ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleReportController extends Controller
{

    public function indexAction()
    {
        return $this->render('OjsReportBundle:article:index.html.twig');
    }

}
