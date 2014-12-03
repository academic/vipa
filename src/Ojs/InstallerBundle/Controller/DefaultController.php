<?php

namespace Ojs\InstallerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OjsInstallerBundle:Default:index.html.twig');
    }

    public function checkAction()
    {
        return $this->render('OjsInstallerBundle:Default:check.html.twig');
    }

    public function configureAction()
    {
        return $this->render("OjsInstallerBundle:Default:configure.html.twig");
    }

    public function setupAction()
    {
        return $this->render("OjsInstallerBundle:Default:setup.html.twig");
    }

    public function summaryAction()
    {
        return $this->render("OjsInstallerBundle:Default:summary.html.twig");
    }
}
