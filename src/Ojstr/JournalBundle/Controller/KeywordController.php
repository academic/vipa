<?php

namespace Ojstr\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojstr\JournalBundle\Entity\Keyword;
use Ojstr\JournalBundle\Form\KeywordType;

/**
 * Keyword controller.
 *
 */
class KeywordController extends Controller {

    /**
     * Lists all Keyword entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjstrJournalBundle:Keyword')->findAll();
        return $this->render('OjstrJournalBundle:Keyword:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new Keyword entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Keyword();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setTranslatableLocale($request->getLocale());
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('keyword_show', array('id' => $entity->getId())));
        }
        return $this->render('OjstrJournalBundle:Keyword:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Keyword entity.
     *
     * @param Keyword $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Keyword $entity) {
        $form = $this->createForm(new KeywordType(), $entity, array(
            'action' => $this->generateUrl('keyword_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));
        return $form;
    }

    /**
     * Displays a form to create a new Keyword entity.
     *
     */
    public function newAction() {
        $entity = new Keyword();
        $form = $this->createCreateForm($entity);
        return $this->render('OjstrJournalBundle:Keyword:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Keyword entity.
     *
     */
    public function showAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Keyword')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        $deleteForm = $this->createDeleteForm($id);
        return $this->render('OjstrJournalBundle:Keyword:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing Keyword entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Keyword')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        return $this->render('OjstrJournalBundle:Keyword:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Keyword entity.
     *
     * @param Keyword $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Keyword $entity) {
        $form = $this->createForm(new KeywordType(), $entity, array(
            'action' => $this->generateUrl('keyword_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }

    /**
     * Edits an existing Keyword entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Keyword')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $entity->setTranslatableLocale($request->getLocale());
            $em->flush();
            return $this->redirect($this->generateUrl('keyword_edit', array('id' => $id)));
        }
        return $this->render('OjstrJournalBundle:Keyword:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Keyword entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjstrJournalBundle:Keyword')->find($id);
            if (!$entity) {
                throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
            }
            $entity->setTranslatableLocale($request->getLocale());
            $em->remove($entity);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('keyword'));
    }

    /**
     * Creates a form to delete a Keyword entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('keyword_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => $this->get('translator')->trans('Delete'), 'attr' => array('onclick' => 'return confirm("' . $this->get('translator')->trans('Are you sure?') . '"); ')))
                        ->getForm();
    }

}
