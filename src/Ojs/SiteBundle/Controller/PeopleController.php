<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

class PeopleController extends Controller
{
    /**
     * @param int $page
     * @param array $filter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page = 1, $filter = null)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('OjsUserBundle:User')->findAll();

        $adapter = new ArrayAdapter($users);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(20);
        $pagerfanta->setCurrentPage($page);
        $people = $pagerfanta->getCurrentPageResults();

        $subjects = $em->getRepository('OjsJournalBundle:Subject')->findAll();

        $data = [
            'people' => $people,
            'subjects' => $subjects,
            'pagerfanta' => $pagerfanta,
        ];

        return $this->render('OjsSiteBundle:People:index.html.twig', $data);
    }
}
