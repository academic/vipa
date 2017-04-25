<?php

namespace Vipa\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Vipa\AdminBundle\Form\Type\PeriodType;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\JournalBundle\Entity\Journal;
use Vipa\JournalBundle\Entity\Period;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * Period controller.
 *
 */
class AdminPeriodController extends Controller
{
    /**
     * Lists all Period entities.
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('VipaJournalBundle:Period');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('vipa_admin_period_show', 'id');
        $rowAction[] = $gridAction->editAction('vipa_admin_period_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('vipa_admin_period_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('VipaAdminBundle:AdminPeriod:index.html.twig', $data);
    }

    /**
     * Creates a new Period entity.
     *
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $entity = new Period();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('vipa_admin_period_show', ['id' => $entity->getId()]);
        }

        return $this->render(
            'VipaAdminBundle:AdminPeriod:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Period entity.
     *
     * @param Period $entity The entity
     *
     * @return Form The form
     */
    private function createCreateForm(Period $entity)
    {
        $form = $this->createForm(
            new PeriodType(),
            $entity,
            array(
                'action' => $this->generateUrl('vipa_admin_period_create'),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Period entity.
     *
     */
    public function newAction()
    {
        $entity = new Period();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'VipaAdminBundle:AdminPeriod:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Period entity.
     *
     * @param Period $entity
     * @return Response
     */
    public function showAction(Period $entity)
    {
        $this->throw404IfNotFound($entity);

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_admin_period'.$entity->getId());

        return $this->render(
            'VipaAdminBundle:AdminPeriod:show.html.twig',
            array(
                'entity' => $entity,
                'token' => $token,
            )
        );
    }

    /**
     * Displays a form to edit an existing Period entity.
     *
     * @param Period $entity
     * @return Response
     */
    public function editAction(Period $entity)
    {
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'VipaAdminBundle:AdminPeriod:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Period entity.
     *
     * @param Period $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Period $entity)
    {
        $form = $this->createForm(
            new PeriodType(),
            $entity,
            array(
                'action' => $this->generateUrl('vipa_admin_period_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Period entity.
     *
     * @param Request $request
     * @param Period $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, Period $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $this->throw404IfNotFound($entity);

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('vipa_admin_period_edit', ['id' => $entity->getId()]);
        }

        return $this->render(
            'VipaAdminBundle:AdminPeriod:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param Request $request
     * @param Period $entity
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Period $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('vipa_admin_period'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $this->get('vipa_core.delete.service')->check($entity);
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('vipa_admin_period_index');
    }
}
