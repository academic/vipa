<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\SubmissionSetting;
use Ojs\JournalBundle\Form\Type\SubmissionSettingType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SubmissionSettingController extends Controller
{
    public function editAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'submissionSettings')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's section!");
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:SubmissionSetting')->findOneBy([]);
        if(!$entity){
            $entity = $this->createSubmissionSetting($journal);
        }
        $editForm = $this->createEditForm($entity);

        $editForm->handleRequest($request);

        if($editForm->isValid() && $request->getMethod() == 'PUT'){
            $em->flush();

            $this->successFlashBag('successful.update');
        }

        return $this->render(
            'OjsJournalBundle:SubmissionSetting:edit.html.twig',
            array(
                'entity' => $entity,
                'form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Section entity.
     *
     * @param SubmissionSetting $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(SubmissionSetting $entity)
    {
        $form = $this->createForm(
            new SubmissionSettingType(),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'ojs_journal_settings_submission',
                    array('journalId' => $entity->getJournal()->getId())
                ),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * @param Journal $journal
     * @return SubmissionSetting
     */
    private function createSubmissionSetting(Journal $journal)
    {
        $em = $this->getDoctrine()->getManager();
        $submissionSetting = new SubmissionSetting();
        $submissionSetting
            ->setJournal($journal)
            ->setSubmissionEnabled(true);
        $em->persist($submissionSetting);
        $em->flush();

        return $submissionSetting;
    }
}
