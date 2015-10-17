<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController;

class DashboardController extends OjsController
{
    public function indexAction()
    {
        return $this->render('OjsJournalBundle:Dashboard:dashboard.html.twig');
    }
}
