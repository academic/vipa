<?php

namespace Ojstr\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ojstr\JournalBundle\Entity\ArticleAuthor;
use Ojstr\JournalBundle\Form\ArticleAuthorType;

/**
 * ArticleAuthor controller.
 *
 */
class ArticleAuthorController extends Controller
{

    /**
     * Lists all ArticleAuthor entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjstrJournalBundle:ArticleAuthor')->findAll();

        return $this->render('OjstrJournalBundle:ArticleAuthor:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new ArticleAuthor entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ArticleAuthor();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('articleauthor_show', array('id' => $entity->getId())));
        }

        return $this->render('OjstrJournalBundle:ArticleAuthor:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ArticleAuthor entity.
     *
     * @param ArticleAuthor $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ArticleAuthor $entity)
    {
        $form = $this->createForm(new ArticleAuthorType(), $entity, array(
            'action' => $this->generateUrl('articleauthor_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ArticleAuthor entity.
     *
     */
    public function newAction()
    {
        $entity = new ArticleAuthor();
        $form   = $this->createCreateForm($entity);

        return $this->render('OjstrJournalBundle:ArticleAuthor:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ArticleAuthor entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrJournalBundle:ArticleAuthor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleAuthor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrJournalBundle:ArticleAuthor:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ArticleAuthor entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrJournalBundle:ArticleAuthor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleAuthor entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrJournalBundle:ArticleAuthor:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ArticleAuthor entity.
    *
    * @param ArticleAuthor $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ArticleAuthor $entity)
    {
        $form = $this->createForm(new ArticleAuthorType(), $entity, array(
            'action' => $this->generateUrl('articleauthor_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ArticleAuthor entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrJournalBundle:ArticleAuthor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleAuthor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('articleauthor_edit', array('id' => $id)));
        }

        return $this->render('OjstrJournalBundle:ArticleAuthor:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ArticleAuthor entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjstrJournalBundle:ArticleAuthor')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ArticleAuthor entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('articleauthor'));
    }

    /**
     * Creates a form to delete a ArticleAuthor entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('articleauthor_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
