<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\JournalBundle\Entity\Issue;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojs\JournalBundle\Entity\IssueFile;
use Ojs\JournalBundle\Form\Type\IssueFileType;

/**
 * IssueFile controller.
 */
class IssueFileController extends Controller
{
    /**
     * Lists all IssueFile entities.
     *
     */
    public function indexAction()
    {

        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('VIEW', $journal, 'issuefiles')) {
            throw new AccessDeniedException("You are not authorized for view this journal's issue files!");
        }
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjsJournalBundle:IssueFile')->findAll();

        return $this->render('OjsJournalBundle:IssueFile:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new IssueFile entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new IssueFile();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var Issue $issue */
            $issue = $em->find("OjsJournalBundle:Issue",$entity->getIssueId());
            $entity->setIssue($issue);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('issue_edit', array('id' => $entity->getIssueId())));
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
            'action' => $this->generateUrl('admin_issuefile_create'),
            'method' => 'POST',
            "languages" => $langs
        ]);


        return $form;
    }

    /**
     * Displays a form to create a new IssueFile entity.
     *
     */
    public function newAction(Request $request, $issue)
    {
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
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:IssueFile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find IssueFile entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsJournalBundle:IssueFile:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing IssueFile entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:IssueFile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find IssueFile entity.');
        }

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
        $form = $this->createForm(new IssueFileType(), $entity, array(
            'action' => $this->generateUrl('admin_issuefile_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'languages' => $langs
        ));


        return $form;
    }

    /**
     * Edits an existing IssueFile entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:IssueFile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find IssueFile entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_issuefile_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:IssueFile:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a IssueFile entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjsJournalBundle:IssueFile')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find IssueFile entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_issuefile'));
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
            ->setAction($this->generateUrl('admin_issuefile_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
