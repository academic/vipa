<?php

namespace Ojstr\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('OjstrSearchBundle:Default:index.html.twig');
    }

}
