<?php

namespace Ojs\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

class PeopleController extends Controller
{
    /**
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $usersWithRoles = $em
            ->getRepository('OjsUserBundle:UserJournalRole')
            ->getUsersWithRoles();

        $adapter = new ArrayAdapter($usersWithRoles);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(20);
        $pagerfanta->setCurrentPage($page);

        return $this->render('OjsSiteBundle:People:index.html.twig', ['people' => $usersWithRoles, 'pagerfanta' => $pagerfanta]);
    }

    /**
     * @param int $id User ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        return $this->render('OjsSiteBundle:People:show.html.twig');
    }
}
