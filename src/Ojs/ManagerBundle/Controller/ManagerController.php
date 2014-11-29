<?php

namespace Ojs\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ManagerController extends Controller
{

    public function journalSettingsAction()
    {
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
    }

    public function userIndexAction()
    {
        $super_admin = $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        if ($super_admin) {
            return $this->redirect($this->generateUrl('dashboard_admin'));
        }
        return $this->render('OjsManagerBundle:User:userwelcome.html.twig');
    }

    /**
     * list journal users 
     */
    public function usersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data['journal'] = $this->get("ojs.journal_service")->getSelectedJournal();
        $data['entities'] = $em->getRepository('OjsUserBundle:UserJournalRole')->findAll();
        return $this->render('OjsManagerBundle:Manager:users.html.twig', $data);
    }

    public function roleUser()
    {
        $em = $this->getDoctrine()->getManager();
        $data['journal'] = $this->get("ojs.journal_service")->getSelectedJournal();
        $data['entities'] = $em->getRepository('OjsUserBundle:UserJournalRole')->findAll();
        return $this->render('OjsManagerBundle:Manager:role_users.html.twig', $data);
    }

}
