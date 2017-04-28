<?php

namespace Vipa\InstallerBundle\Controller;

use Vipa\CoreBundle\Controller\VipaController as Controller;

class SummaryController extends Controller
{

    public function summaryAction()
    {
        $data['page'] = 'summary';

        return $this->render("VipaInstallerBundle:Default:summary.html.twig", array('data' => $data));
    }
}
