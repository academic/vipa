<?php

namespace Ojstr\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ManagerController extends Controller {

    public function indexAction() {
        return $this->render('OjstrManagerBundle:Default:index.html.twig');
    }
    
     public function getManagerAction() {
        return $this->render('OjstrManagerBundle:Manager:index.html.twig');
    }

}
