<?php

namespace Ojstr\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function dashboardCheckAction()
    {
        $superAdmin = $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        $editor = $this->container->get('security.context')->isGranted('ROLE_EDITOR');

        if ($superAdmin) {
            return $this->redirect($this->generateUrl('dashboard_admin'));
        } elseif ($editor) {
            return $this->redirect($this->generateUrl('dashboard_editor'));
        } else {
            return $this->redirect($this->generateUrl('ojs_user_index'));
        }
    }

    public function dashboardAction()
    {
        $super_admin = $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        if ($super_admin) {
            return $this->render('OjstrManagerBundle:Admin:dashboard.html.twig');
        } else {
            return $this->redirect($this->generateUrl('dashboard_editor'));
        }
    }

}
