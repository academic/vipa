<?php

namespace Ojstr\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EditorController extends Controller {

    /**
     * Global index page
     * @return type
     */
    public function indexAction() {
        return $this->render('OjstrManagerBundle:Editor:index.html.twig');
    }

    /**
     * 
     * Dashboard for editors
     */
    public function dashboardAction() {
        return $this->render('OjstrManagerBundle:Editor:dashboard.html.twig');
    }

}
