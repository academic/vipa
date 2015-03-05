<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Helper\ActionHelper;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Author;
use Ojs\JournalBundle\Form\AuthorType;

/**
 * Author controller.
 *
 */
class AuthorController extends Controller
{
    /**
     * Lists all Author entities.
     *
     */
    public function indexAction()
    {
        $source = new Entity('OjsJournalBundle:Author');
        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = ActionHelper::showAction('author_show', 'id');
        $rowAction[] = ActionHelper::editAction('author_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('author_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;
        return $grid->getGridResponse('OjsJournalBundle:Author:index.html.twig',$data);
    }

    /**
     * Creates a new Author entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Author();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('author_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsJournalBundle:Author:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Author entity.
     *
     * @param Author $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Author $entity)
    {
        $form = $this->createForm(new AuthorType(), $entity, array(
            'action' => $this->generateUrl('author_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Author entity.
     *
     */
    public function newAction()
    {
        $entity = new Author();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:Author:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Author entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Author')->find($id);
        $this->throw404IfNotFound($entity);

        return $this->render('OjsJournalBundle:Author:show.html.twig', array(
                    'entity' => $entity));
    }

    /**
     * Displays a form to edit an existing Author entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Author')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:Author:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Creates a form to edit a Author entity.
     *
     * @param Author $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Author $entity)
    {
        $form = $this->createForm(new AuthorType(), $entity, array(
            'action' => $this->generateUrl('author_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Author entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Author')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('author_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:Author:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a Author entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Author')->find($id);
        $this->throw404IfNotFound($entity);
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('author'));
    }

}
