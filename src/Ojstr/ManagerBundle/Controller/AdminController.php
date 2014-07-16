<?php

namespace Ojstr\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller {

    /**
     * Global index page
     * @return type
     */
    public function indexAction() {
        /** @var $siteManager \KnpU\QADayBundle\Site\SiteManager */
        $journalDomain = $this->container->get('journal_domain');
        echo "<pre>--";
        $currentJournal = $journalDomain->getCurrentJournal();
        print_r( $currentJournal->getTitle());
        exit();

        return $this->render('OjstrManagerBundle::index.html.twig');
    }

    public function dashboardCheckAction() {
        $super_admin = $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        if ($super_admin) {
            return $this->redirect($this->generateUrl('dashboard_admin'));
        } else {
            return $this->redirect($this->generateUrl('dashboard_editor'));
        }
    }

    public function dashboardAction() {
        $super_admin = $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        if ($super_admin) {
            return $this->render('OjstrManagerBundle:Admin:dashboard.html.twig');
        } else {
            return $this->redirect($this->generateUrl('dashboard_editor'));
        }
    }

}
