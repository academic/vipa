<?php

namespace Ojs\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ManagerController extends Controller
{

    /**
     * list journal users 
     */
    public function usersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $selectedJournalId = $this->get('session')->get("selectedJournalId");
        $data['journal'] = $em->getRepository('OjsJournalBundle:Journal')->find($selectedJournalId);
        $data['entities'] = $em->getRepository('OjsUserBundle:UserJournalRole')->findAll();
        return $this->render('OjsManagerBundle:Manager:users.html.twig', $data);
    }

    public function roleUser()
    {
        $em = $this->getDoctrine()->getManager();
        $selectedJournalId = $this->get('session')->get("selectedJournalId");
        $data['journal'] = $em->getRepository('OjsJournalBundle:Journal')->find($selectedJournalId);
        $data['entities'] = $em->getRepository('OjsUserBundle:UserJournalRole')->findAll();

        return $this->render('OjsManagerBundle:Manager:role_users.html.twig', $data);
    }

}
