<?php

namespace Vipa\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Vipa\AdminBundle\Form\Type\LangType;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\JournalBundle\Entity\Lang;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * Lang controller.
 *
 */
class AdminLanguageController extends Controller
{

    /**
     * Lists all Lang entities.
     *
     */
    public function indexAction()
    {
        $source = new Entity('VipaJournalBundle:Lang');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('vipa_admin_language_show', 'id');
        $rowAction[] = $gridAction->editAction('vipa_admin_language_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('vipa_admin_language_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('VipaAdminBundle:AdminLanguage:index.html.twig', $data);
    }

    /**
     * Creates a new Lang entity.
     *
     * @param  Request                                                                                       $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('vipa_admin_language_show', ['id' => $entity->getId()]);
        }

        return $this->render(
            'VipaAdminBundle:AdminLanguage:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
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
        $form = $this->createForm(
            new LangType(),
            $entity,
            array(
                'action' => $this->generateUrl('vipa_admin_language_create'),
                'method' => 'POST',
            )
        );

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

        return $this->render(
            'VipaAdminBundle:AdminLanguage:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Lang entity.
     * @param  Lang                                       $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Lang $entity)
    {
        $this->throw404IfNotFound($entity);
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_admin_language'.$entity->getId());

        return $this->render(
            'VipaAdminBundle:AdminLanguage:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing Lang entity.
     * @param  Lang                                       $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Lang $entity)
    {
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'VipaAdminBundle:AdminLanguage:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
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
        $form = $this->createForm(
            new LangType(),
            $entity,
            array(
                'action' => $this->generateUrl('vipa_admin_language_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * Edits an existing Lang entity.
     * @param  Request                                                                                       $request
     * @param  Lang                                                                                          $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, Lang $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('vipa_admin_language_edit', ['id' => $entity->getId()]);
        }

        return $this->render(
            'VipaAdminBundle:AdminLanguage:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request                                            $request
     * @param  Lang                                               $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Lang $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('vipa_admin_language'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $this->get('vipa_core.delete.service')->check($entity);
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('vipa_admin_language_index');
    }
}
