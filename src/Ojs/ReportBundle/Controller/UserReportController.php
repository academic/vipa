<?php

namespace Ojs\ReportBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserReportController extends Controller
{

    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('VIEW', $journal, 'report')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        return $this->render('OjsReportBundle:user:index.html.twig');
    }
}
