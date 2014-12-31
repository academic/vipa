<?php

namespace Ojs\InstallerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConfigController extends Controller {

    public function configureAction() {
        $data['page']='config';
        return $this->render("OjsInstallerBundle:Default:configure.html.twig", array('data' => $data));
    }

}
