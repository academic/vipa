<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Helper\ActionHelper;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalDesign;
use Ojs\JournalBundle\Form\JournalDesignType;

/**
 * JournalDesign controller.
 *
 */
class JournalDesignController extends Controller
{

    /**
     * Lists all JournalDesign entities.
     *
     */
    public function indexAction()
    {
        $source = new Entity('OjsJournalBundle:JournalDesign');
        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = ActionHelper::showAction('admin_journaldesign_show', 'id');
        $rowAction[] = ActionHelper::editAction('admin_journaldesign_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('admin_journaldesign_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:JournalDesign:index.html.twig', $data);
    }
    /**
     * Creates a new JournalDesign entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new JournalDesign();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('admin_journaldesign_show', ['id' => $entity->getId()]);
        }

        return $this->render('OjsJournalBundle:JournalDesign:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a JournalDesign entity.
     *
     * @param JournalDesign $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(JournalDesign $entity)
    {
        $form = $this->createForm(new JournalDesignType(), $entity, array(
            'action' => $this->generateUrl('admin_journaldesign_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new JournalDesign entity.
     *
     */
    public function newAction()
    {
        $entity = new JournalDesign();
        $form   = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:JournalDesign:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a JournalDesign entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalDesign')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        return $this->render('OjsJournalBundle:JournalDesign:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing JournalDesign entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalDesign')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:JournalDesign:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a JournalDesign entity.
     *
     * @param JournalDesign $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(JournalDesign $entity)
    {
        $form = $this->createForm(new JournalDesignType(), $entity, array(
            'action' => $this->generateUrl('admin_journaldesign_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing JournalDesign entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalDesign')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('admin_journaldesign_edit', ['id' => $id]);
        }

        return $this->render('OjsJournalBundle:JournalDesign:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Deletes a JournalDesign entity.
     * @param  JournalDesign                                      $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(JournalDesign $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('admin_JournalDesign');
    }
}
