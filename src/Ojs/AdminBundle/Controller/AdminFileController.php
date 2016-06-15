<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\AdminBundle\Entity\AdminFile;
use Ojs\AdminBundle\Form\Type\AdminFileType;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\CoreBundle\Helper\StringHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * AdminFilecontroller.
 *
 */
class AdminFileController extends Controller
{

    /**
     * Lists all AdminFileentities.
     *
     */
    public function indexAction()
    {
        $source = new Entity('OjsAdminBundle:AdminFile');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('ojs_admin_file_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_file_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_file_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsAdminBundle:AdminFile:index.html.twig', $data);
    }

    /**
     * Creates a new AdminFileentity.
     *
     * @param  Request                                                                                       $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new AdminFile();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $rootDir = $this->getParameter('kernel.root_dir');
            $path = $rootDir . '/../web/uploads/files/' . $entity->getPath();
            $entity->setSize(StringHelper::formatBytes(filesize($path)));

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('ojs_admin_file_show', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsAdminBundle:AdminFile:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a AdminFileentity.
     *
     * @param AdminFile $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AdminFile $entity)
    {
        $form = $this->createForm(
            new AdminFileType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_file_create'),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new AdminFileentity.
     *
     */
    public function newAction()
    {
        $entity = new AdminFile();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminFile:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a AdminFileentity.
     * @param  AdminFile $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(AdminFile $entity)
    {
        $this->throw404IfNotFound($entity);
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_file'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminFile:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing AdminFileentity.
     * @param  AdminFile $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(AdminFile $entity)
    {
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminFile:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a AdminFileentity.
     *
     * @param AdminFile $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(AdminFile $entity)
    {
        $form = $this->createForm(
            new AdminFileType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_file_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * Edits an existing AdminFileentity.
     * @param  Request $request
     * @param  AdminFile $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, AdminFile $entity)
    {
        $this->throw404IfNotFound($entity);

        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('ojs_admin_file_edit', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsAdminBundle:AdminFile:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request $request
     * @param  AdminFile $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, AdminFile $entity)
    {
        $this->throw404IfNotFound($entity);

        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_file'.$entity->getId());

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $this->get('ojs_core.delete.service')->check($entity);

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_admin_file_index');
    }
}
