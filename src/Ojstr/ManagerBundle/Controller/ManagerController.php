<?php

namespace Ojstr\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ManagerController extends Controller {

    /**
     * Global index page
     * @return type
     */
    public function indexAction() {
        $super_admin = $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        $dashboard_path = $super_admin ? 'ojstr_index_admin' : 'ojstr_index_manager';
        return $this->render('OjstrManagerBundle:Default:index.html.twig', array('dashboard_path' => $dashboard_path));
    }

    public function getManagerAction() {
        return $this->render('OjstrManagerBundle:Manager:index.html.twig');
    }

    public function getAdminAction() {
        return $this->render('OjstrManagerBundle:Manager:index_super.html.twig');
    }

}
