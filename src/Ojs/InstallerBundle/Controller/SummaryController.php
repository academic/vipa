<?php

namespace Ojs\InstallerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SummaryController extends Controller {

    public function summaryAction() {
        $data['page']='summary';
        return $this->render("OjsInstallerBundle:Default:summary.html.twig", array('data' => $data));
    }

}
