<?php

namespace Ojs\ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleReportController extends Controller
{

    public function indexAction()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $data = $dm->getRepository('OjsAnalyticsBundle:ObjectViews');
        return $this->render('OjsReportBundle:article:index.html.twig');
    }

}
