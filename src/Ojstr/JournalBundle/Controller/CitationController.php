<?php

namespace Ojstr\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojstr\JournalBundle\Entity\Citation;
use Ojstr\JournalBundle\Form\CitationType;

/**
 * Citation controller.
 *
 */
class CitationController extends Controller {

    /**
     * Lists all Citation entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjstrJournalBundle:Citation')->findAll();

        return $this->render('OjstrJournalBundle:Citation:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new Citation entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Citation();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('citation_show', array('id' => $entity->getId())));
        }

        return $this->render('OjstrJournalBundle:Citation:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Citation entity.
     *
     * @param Citation $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Citation $entity) {
        $form = $this->createForm(new CitationType(), $entity, array(
            'action' => $this->generateUrl('citation_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Citation entity.
     *
     */
    public function newAction() {
        $entity = new Citation();
        $form = $this->createCreateForm($entity);
        return $this->render('OjstrJournalBundle:Citation:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Citation for an Article
     *
     */
    public function articleAction($article_id = NULL) {
        $em = $this->getDoctrine()->getManager();
        $article = !empty($article_id) ?
                $em->getRepository('OjstrJournalBundle:Article')->find($article_id) :
                NULL;
        $entity = new Citation();
        $entity->addArticle($article);
        $form = $this->createForm(new \Ojstr\JournalBundle\Form\ArticleCitationType(), $entity);
        return $this->render('OjstrJournalBundle:Citation:article.html.twig', array(
                    'entity' => $entity,
                    'article' => $article,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Citation entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrJournalBundle:Citation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Citation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrJournalBundle:Citation:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing Citation entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrJournalBundle:Citation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Citation entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrJournalBundle:Citation:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Citation entity.
     *
     * @param Citation $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Citation $entity) {
        $form = $this->createForm(new CitationType(), $entity, array(
            'action' => $this->generateUrl('citation_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Citation entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrJournalBundle:Citation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Citation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('citation_edit', array('id' => $id)));
        }

        return $this->render('OjstrJournalBundle:Citation:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Citation entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjstrJournalBundle:Citation')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Citation entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('citation'));
    }

    /**
     * Creates a form to delete a Citation entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('citation_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array(
                            'label' => $this->get('translator')->trans('Delete'),
                            'attr' => array('class' => 'btn btn-danger', 'onclick' => 'return confirm("' .
                                $this->get('translator')->trans('Are you sure?') . '"); ')
                        ))
                        ->getForm()
        ;
    }

}
