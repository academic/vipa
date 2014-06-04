<?php

namespace Ojstr\RefBaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('OjstrRefBaseBundle:Default:index.html.twig');
    }

}
