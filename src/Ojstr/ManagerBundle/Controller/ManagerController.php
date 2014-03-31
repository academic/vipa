<?php

namespace Ojstr\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ManagerController extends Controller {

    public function indexAction() {
        return $this->render('OjstrManagerBundle:Manager:index.html.twig');
    }

}
