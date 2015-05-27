<?php

namespace Ojs\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PeopleController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usersWithRoles = $em
            ->getRepository('OjsUserBundle:UserJournalRole')
            ->getUsersWithRoles();

        return $this->render('OjsSiteBundle:People:index.html.twig', ['people' => $usersWithRoles]);
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
