<?php

namespace Ojs\UserBundle\Controller;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\UserBundle\Entity\MailLog;
use Ojs\UserBundle\Form\MailLogType;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use Ojs\Common\Helper\ActionHelper;

/**
 * MailLog controller.
 *
 */
class MailLogController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $source = new Entity('OjsUserBundle:MailLog');
        $grid = $this->get('grid')->setSource($source);
        $actionColumn = new ActionsColumn("actions", "actions");
        $rowAction = [];
        ActionHelper::setup($this->get('security.csrf.token_manager'));
        $rowAction[] = ActionHelper::showAction('admin_maillog_show', 'id');
        $rowAction[] = ActionHelper::editAction('admin_maillog_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('admin_maillog_delete', 'id');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;
        return $grid->getGridResponse('OjsUserBundle:MailLog:index.html.twig', $data);
    }

    /**
     * Creates a new MailLog entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $entity = new MailLog();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirect($this->generateUrl('admin_maillog_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsUserBundle:MailLog:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a MailLog entity.
     *
     * @param  MailLog $entity The entity
     * @return Form    The form
     */
    private function createCreateForm(MailLog $entity)
    {
        $form = $this->createForm(new MailLogType(), $entity, array(
            'action' => $this->generateUrl('admin_maillog_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new MailLog entity.
     *
     * @return Response
     */
    public function newAction()
    {
        $entity = new MailLog();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsUserBundle:MailLog:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a MailLog entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:MailLog')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        return $this->render('OjsUserBundle:MailLog:show.html.twig', array(
            'entity' => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing MailLog entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var MailLog $entity */
        $entity = $em->getRepository('OjsUserBundle:MailLog')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        $editForm = $this->createEditForm($entity);

        return $this->render('OjsUserBundle:MailLog:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a MailLog entity.
     * @param  MailLog $entity The entity
     * @return Form    The form
     */
    private function createEditForm(MailLog $entity)
    {
        $form = $this->createForm(new MailLogType(), $entity, array(
            'action' => $this->generateUrl('admin_maillog_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing MailLog entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var MailLog $entity */
        $entity = $em->getRepository('OjsUserBundle:MailLog')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect($this->generateUrl('admin_maillog_edit', array('id' => $id)));
        }

        return $this->render('OjsUserBundle:MailLog:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a MailLog entity.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:MailLog')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirect($this->generateUrl('admin_maillog'));
    }
}
