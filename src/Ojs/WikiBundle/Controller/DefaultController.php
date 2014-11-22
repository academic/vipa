<?php

namespace Ojs\WikiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OjsWikiBundle:Default:index.html.twig', array('name' => $name));
    }
}
