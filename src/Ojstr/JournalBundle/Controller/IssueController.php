<?php

namespace Ojstr\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojstr\JournalBundle\Entity\Issue;
use Ojstr\JournalBundle\Form\IssueType;
use Ojstr\Common\Helper\CommonFormHelper as CommonFormHelper;

/**
 * Issue controller.
 *
 */
class IssueController extends Controller {

    /**
     * Lists all Issue entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjstrJournalBundle:Issue')->findAll();
        return $this->render('OjstrJournalBundle:Issue:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new Issue entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Issue();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('issue_show', array('id' => $entity->getId())));
        }
        return $this->render('OjstrJournalBundle:Issue:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Issue entity.
     *
     * @param Issue $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Issue $entity) {
        $form = $this->createForm(new IssueType(), $entity, array(
            'action' => $this->generateUrl('issue_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));
        return $form;
    }

    /**
     * Displays a form to create a new Issue entity.
     *
     */
    public function newAction() {
        $entity = new Issue();
        $form = $this->createCreateForm($entity);
        return $this->render('OjstrJournalBundle:Issue:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Issue entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Issue')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        $deleteForm = $this->createDeleteForm($id);
        return $this->render('OjstrJournalBundle:Issue:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing Issue entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Issue')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        return $this->render('OjstrJournalBundle:Issue:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Issue entity.
     *
     * @param Issue $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Issue $entity) {
        $form = $this->createForm(new IssueType(), $entity, array(
            'action' => $this->generateUrl('issue_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }

    /**
     * Edits an existing Issue entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Issue')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('issue_edit', array('id' => $id)));
        }
        return $this->render('OjstrJournalBundle:Issue:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Issue entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjstrJournalBundle:Issue')->find($id);
            if (!$entity) {
                throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
            }
            $em->remove($entity);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('issue'));
    }

    /**
     * Creates a form to delete a Issue entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        $formHelper = new CommonFormHelper();
        return $formHelper->createDeleteForm($this, $id,'issue_delete');
    }

}
