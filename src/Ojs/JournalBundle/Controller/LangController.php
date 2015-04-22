<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Helper\ActionHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojs\JournalBundle\Entity\Lang;
use Ojs\JournalBundle\Form\LangType;

/**
 * Lang controller.
 *
 */
class LangController extends Controller
{

    /**
     * Lists all Lang entities.
     *
     */
    public function indexAction()
    {
        $source = new Entity('OjsJournalBundle:Lang');
        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = ActionHelper::showAction('lang_show', 'id');
        $rowAction[] = ActionHelper::editAction('lang_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('lang_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;
        return $grid->getGridResponse('OjsJournalBundle:Lang:index.html.twig',$data);
    }

    /**
     * Creates a new Lang entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Lang();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('lang_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsJournalBundle:Lang:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Lang entity.
     *
     * @param Lang $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Lang $entity)
    {
        $form = $this->createForm(new LangType(), $entity, array(
            'action' => $this->generateUrl('lang_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Lang entity.
     *
     */
    public function newAction()
    {
        $entity = new Lang();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:Lang:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Lang entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:Lang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        return $this->render('OjsJournalBundle:Lang:show.html.twig', array(
                    'entity' => $entity
        ));
    }

    /**
     * Displays a form to edit an existing Lang entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:Lang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:Lang:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Creates a form to edit a Lang entity.
     *
     * @param Lang $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Lang $entity)
    {
        $form = $this->createForm(new LangType(), $entity, array(
            'action' => $this->generateUrl('lang_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Lang entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:Lang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('lang_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:Lang:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Lang entity.
     *
     */
    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Lang')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('lang'));
    }

}
