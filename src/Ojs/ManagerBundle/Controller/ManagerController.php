<?php

namespace Ojs\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojs\JournalBundle\Form\JournalType;

class ManagerController extends Controller
{

    public function journalSettingsAction($journalId = null)
    {
        if (!$journalId) {
            $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        } else {
            $em = $this->getDoctrine()->getManager();
            $journal = $em->getRepository('OjsJournalBundle:Journal')->find($journalId);
        }
        $form = $this->createForm(new JournalType(), $journal, array(
            'action' => $this->generateUrl('journal_update', array('id' => $journal->getId())),
            'method' => 'PUT',
        ));
        return $this->render('OjsManagerBundle:Manager:journal_settings.html.twig', array(
                    'journal' => $journal,
                    'form' => $form->createView(),
        ));
    }

    public function userIndexAction()
    {

        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $mySteps = [];
        if ($journal) {
            $dm = $this->get('doctrine_mongodb')->getManager();
            $allowedWorkflowSteps = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                    ->findBy(array('journalid' => $journal->getId()));
            // @todo we should query in a more elegant way  
            // { roles : { $elemMatch : { role : "ROLE_EDITOR" }} })
            // Don't know how to query $elemMatch 
            foreach ($allowedWorkflowSteps as $step) {
                if ($this->checkStepAndUserRoles($step)) {
                    $mySteps[] = $step;
                }
            }
        }
        $waitingTasksCount = [];
        foreach ($mySteps as $step) {
            $countQuery = $dm->getRepository('OjsWorkflowBundle:ArticleReviewStep')
                    ->createQueryBuilder('ars');
            $countQuery->field('stepId')->equals($step->getId());
            $countQuery->field('finishedDate')->equals(null);
            $waitingTasksCount[$step->getId()] = $countQuery->count()->getQuery()->execute();
        }
        $super_admin = $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        if ($super_admin) {
            return $this->redirect($this->generateUrl('dashboard_admin'));
        }
        return $this->render('OjsManagerBundle:User:userwelcome.html.twig', array('mySteps' => $mySteps, 'waitingCount' => $waitingTasksCount));
    }

    private function checkStepAndUserRoles($step)
    {
        $myRoles = $this->get('session')->get('userJournalRoles');
        $stepRoles = $step->getRoles();
        foreach ($myRoles as $myRole) {
            foreach ((array) $stepRoles as $stepRole) {
                if ($stepRole['role'] === $myRole->getRole()) {
                    return true;
                }
            }
        }
        return false;
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
