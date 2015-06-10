<?php

namespace Ojs\ReportBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ArticleReportController extends Controller
{

    public function indexAction()
    {
        /*
        $em = $this->getDoctrine()->getManager();
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $data = $dm->getRepository('OjsAnalyticsBundle:ObjectViews');
        */
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'report')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }

        return $this->render('OjsReportBundle:article:index.html.twig');
    }
}
