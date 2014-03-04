<?php

namespace Ojstr\JournalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('OjstrJournalBundle:Default:index.html.twig');
    }

}
