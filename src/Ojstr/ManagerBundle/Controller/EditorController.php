<?php

namespace Ojstr\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EditorController extends Controller {

    /**
     * Global index page
     * @return type
     */
    public function indexAction() {
        return $this->render('OjstrManagerBundle:Editor:index.html.twig');
    }

    /**
     * 
     * Dashboard for editors
     */
    public function dashboardAction() {
        return $this->render('OjstrManagerBundle:Editor:dashboard.html.twig');
    }

    public function myJournalsAction() {
        $user_id = $this->container->get('security.context')->getToken()->getUser()->getId();
        if (!$user_id) {
            throw new HttpException(403, 'There is a problem while getting user information. Access denied');
        }
        $entities = $this->getDoctrine()->getRepository('OjstrUserBundle:UserJournalRole')
                ->userJournalsWithRoles($user_id);
        return $this->render('OjstrManagerBundle:Editor:myjournals.html.twig', array(
                    'entities' => $entities
        ));
    }

    public function showJournalAction($id) {
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('OjstrJournalBundle:Journal')->find($id);
        return $this->render('OjstrJournalBundle:Journal:role_based/show_editor.html.twig', array('entity' => $journal));
    }

}
