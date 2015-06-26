<?php

namespace Ojs\JournalBundle\Controller;

use Doctrine\ORM\EntityManager;
use Ojs\JournalBundle\Entity\Issue;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\IssueFile;
use Ojs\JournalBundle\Form\Type\IssueFileType;

/**
 * IssueFile controller.
 */
class IssueFileController extends Controller
{
    /**
     * Creates a new IssueFile entity.
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', $journal, 'issues')) {
            throw new AccessDeniedException('You are not authorized for create  issue file for this journal!');
        }

        $entity = new IssueFile();
        $form = $this->createCreateForm($entity);
        $form->submit($request);

        if ($form->isValid()) {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            var_dump($em->getUnitOfWork()->getScheduledEntityInsertions());exit;
            $em->flush();

            return $this->redirect($this->generateUrl('ojs_journal_issue_edit', array('id' => $entity->getIssueId())));
        }

        return $this->render('OjsJournalBundle:IssueFile:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a IssueFile entity.
     *
     * @param IssueFile $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(IssueFile $entity)
    {
        $languages = $this->container->getParameter('languages');
        $langs = [];
        foreach ($languages as $key => $lang) {
            $langs[$lang['code']] = $lang['name'];
        }

        $form = $this->createForm(new IssueFileType(), $entity, [
            'action' => $this->generateUrl('ojs_journal_issue_file_create'),
            'method' => 'POST',
            'languages' => $langs,
        ]);

        return $form;
    }

    /**
     * Displays a form to create a new IssueFile entity.
     */
    public function newAction(Request $request, $issue)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', $journal, 'issues')) {
            throw new AccessDeniedException('You are not authorized for create  issue file for this journal!');
        }
        $entity = new IssueFile();
        $entity->setIssueId($issue);

        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:IssueFile:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a IssueFile entity.
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:IssueFile')->find($id);

        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException('You are not authorized for view this issue file!');
        }
        $this->throw404IfNotFound($entity);

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsJournalBundle:IssueFile:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing IssueFile entity.
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:IssueFile')->find($id);

        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('EDIT', $journal, 'issues')) {
            throw new AccessDeniedException('You are not authorized for edit this  issue file!');
        }

        $this->throw404IfNotFound($entity);

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsJournalBundle:IssueFile:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a IssueFile entity.
     *
     * @param IssueFile $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(IssueFile $entity)
    {
        $languages = $this->container->getParameter('languages');
        $langs = [];
        foreach ($languages as $key => $lang) {
            $langs[$lang['code']] = $lang['name'];
        }
        $form = $this->createForm(new IssueFileType(), $entity, [
            'action' => $this->generateUrl('ojs_journal_issue_file_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
            'languages' => $langs,
        ]);

        return $form;
    }

    /**
     * Edits an existing IssueFile entity.
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:IssueFile')->find($id);

        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('EDIT', $journal, 'issues')) {
            throw new AccessDeniedException('You are not authorized for edit this issue file!');
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find IssueFile entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ojs_journal_issue_file_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:IssueFile:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a IssueFile entity.
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjsJournalBundle:IssueFile')->find($id);

            $journal = $this->get('ojs.journal_service')->getSelectedJournal();

            if (!$this->isGranted('DELETE', $journal, 'issues')) {
                throw new AccessDeniedException('You are not authorized for delete this issue file!');
            }

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find IssueFile entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ojs_journal_issue_file_index'));
    }

    /**
     * Creates a form to delete a IssueFile entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ojs_journal_issue_file_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
