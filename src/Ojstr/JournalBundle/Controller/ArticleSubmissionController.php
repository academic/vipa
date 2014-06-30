<?php

namespace Ojstr\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Ojstr\Common\Controller\OjsController as Controller;
use Ojstr\JournalBundle\Entity\Article;
use Ojstr\JournalBundle\Form\ArticleType;
use Ojstr\Common\Helper\CommonFormHelper as CommonFormHelper;

/**
 * Article controller.
 *
 */
class ArticleSubmissionController extends Controller {

    /**
     * Lists all new Article submissions entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjstrJournalBundle:Article')->findBy(array('status' => 0));
        return $this->render('OjstrJournalBundle:ArticleSubmission:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new Article entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Article();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('article_show', array('id' => $entity->getId())));
        }
        return $this->render('OjstrJournalBundle:Article:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Article entity.
     *
     * @param Article $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Article $entity) {
        $form = $this->createForm(new ArticleType(), $entity, array(
            'action' => $this->generateUrl('article_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));
        return $form;
    }

    /**
     * Displays a form to create a new Article entity.
     *
     */
    public function newAction() {
        $entity = new Article();
        $form = $this->createCreateForm($entity);
        return $this->render('OjstrJournalBundle:Article:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Article entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        /* @var $entity \Ojstr\JournalBundle\Entity\Article  */
        $entity = $em->getRepository('OjstrJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);
        return $this->render('OjstrJournalBundle:Article:show.html.twig', array(
                    'entity' => $entity));
    }

    /**
     * Displays a form to edit an existing Article entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        return $this->render('OjstrJournalBundle:Article:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Creates a form to edit a Article entity.
     *
     * @param Article $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Article $entity) {
        $form = $this->createForm(new ArticleType(), $entity, array(
            'action' => $this->generateUrl('article_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }

    /**
     * Edits an existing Article entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('article_edit', array('id' => $id)));
        }
        return $this->render('OjstrJournalBundle:Article:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a Article entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);
        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('article'));
    }

    /**
     * Creates a form to delete a Article entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        $formHelper = new CommonFormHelper();
        return $formHelper->createDeleteForm($this, $id, 'article_delete');
    }

}
