<?php

namespace Ojs\InstallerBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController as Controller;

class SummaryController extends Controller
{

    public function summaryAction()
    {
        $data['page'] = 'summary';

        return $this->render("OjsInstallerBundle:Default:summary.html.twig", array('data' => $data));
    }
}
