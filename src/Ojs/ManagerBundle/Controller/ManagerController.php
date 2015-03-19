<?php

namespace Ojs\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojs\JournalBundle\Form\JournalType;
use \Symfony\Component\HttpFoundation\Request;

class ManagerController extends Controller {

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

    /**
     * 
     * @param Request $req
     * @param integer $journal
     * @param string $settingName
     * @param string $settingValue if null, funtion will return current value
     * @param boolean $encoded set tru if setting stored as json_encoded
     * @return type
     */
    private function updateJournalSetting($journal, $settingName, $settingValue, $encoded = false)
    {
        $em = $this->getDoctrine()->getManager();
        $setting = $em->
                getRepository('OjsJournalBundle:JournalSetting')->
                findOneBy(array('journal' => $journal, 'setting' => $settingName));

        $settingString = $encoded ? json_encode($settingValue) : $settingValue;
        if ($setting) {
            $setting->setValue($settingString);
        } else {
            $setting = new \Ojs\JournalBundle\Entity\JournalSetting($settingName, $settingString, $journal);
        }
        $em->persist($setting);
        $em->flush();
        return $setting ? ($encoded ? json_decode($setting->getValue()) : $setting->getValue()) : [];
    }

    public function journalSettingsSubmissionAction(Request $req, $journalId = null)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $journal  \Ojs\JournalBundle\Entity\Journal  */
        $journal = !$journalId ?
                $this->get("ojs.journal_service")->getSelectedJournal() :
                $em->getRepository('OjsJournalBundle:Journal')->find($journalId);
        if ($req->getMethod() == 'POST' && !empty($req->get('submissionMandotaryLanguages'))) {
            $this->updateJournalSetting($journal, 'submissionMandotaryLanguages', $req->get('submissionMandotaryLanguages'), true);
        }
        if ($req->getMethod() == 'POST' && !empty($req->get('submissionAbstractTemplate'))) {
            $this->updateJournalSetting($journal, 'submissionAbstractTemplate', $req->get('submissionAbstractTemplate'), false);
        }

        $languages = $journal->getSetting('submissionMandotaryLanguages') ?
                json_decode($journal->getSetting('submissionMandotaryLanguages')->getValue()) :
                null;
        $abstractTemplate = $journal->getSetting('submissionAbstractTemplate') ?
                $journal->getSetting('submissionAbstractTemplate')->getValue():
                null;
        return $this->render('OjsManagerBundle:Manager:journal_settings_submission.html.twig', array(
                    'journal' => $journal,
                    'submissionMandotaryLanguages' => $languages,
                    'submissionAbstractTemplate' => $abstractTemplate,
                    'allLanguages' => $journal->getLanguages()
        ));
    }

    public function userIndexAction()
    {
        $user = $this->getUser();
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
            $countQuery->field('step.$id')->equals(new \MongoId($step->getId()));
            $countQuery->field('finishedDate')->equals(null);
            $waitingTasksCount[$step->getId()] = $countQuery->count()->getQuery()->execute();
        }
        // invited steps 
        $invitedWorkflowSteps = $dm->getRepository('OjsWorkflowBundle:Invitation')
                ->findBy(array('userId' => $user->getId()));

        $super_admin = $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        if ($super_admin) {
            return $this->redirect($this->generateUrl('dashboard_admin'));
        }
        return $this->render('OjsManagerBundle:User:userwelcome.html.twig', array(
                    'mySteps' => $mySteps,
                    'waitingCount' => $waitingTasksCount,
                    'invitedSteps' => $invitedWorkflowSteps));
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
