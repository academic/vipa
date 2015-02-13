<?php

namespace Ojs\ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserReportController extends Controller
{

    public function indexAction()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        return $this->render('OjsReportBundle:user:index.html.twig');
    }

}
