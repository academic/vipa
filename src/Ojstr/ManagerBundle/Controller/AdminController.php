<?php

namespace Ojstr\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller {

    /**
     * Global index page
     * @return type
     */
    public function indexAction() {
        return $this->render('OjstrManagerBundle::index.html.twig');
    }

    public function dashboardAction() {
        $super_admin = $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        if ($super_admin) {
            return $this->render('OjstrManagerBundle:Admin:dashboard.html.twig');
        }else{
            return $this->redirect($this->generateUrl('dashboard_editor'));
        }
    }

}
