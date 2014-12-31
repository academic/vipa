<?php

namespace Ojs\InstallerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SetupController extends Controller {

    public function setupAction() {
        $data['page']='setup';
        return $this->render("OjsInstallerBundle:Default:setup.html.twig", array('data' => $data));
    }

}
