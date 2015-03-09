<?php

namespace Ojs\InstallerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {

    public function indexAction() {
        $data['page']='home';
        return $this->render('OjsInstallerBundle:Default:index.html.twig', array('data' => $data));
    }

}
