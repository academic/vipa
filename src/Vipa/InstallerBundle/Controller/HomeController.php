<?php

namespace Vipa\InstallerBundle\Controller;

use Vipa\CoreBundle\Controller\VipaController as Controller;

class HomeController extends Controller
{

    public function indexAction()
    {
        $data['page'] = 'home';

        return $this->render('VipaInstallerBundle:Default:index.html.twig', array('data' => $data));
    }
}
