<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\AdminBundle\Form\Type\PersonTitleType;
use Ojs\CoreBundle\Controller\OjsController;
use Ojs\JournalBundle\Entity\PersonTitle;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;
use APY\DataGridBundle\Grid\Row;

/**
 * PersonTitle controller.
 *
 */
class AdminPersonTitleController extends OjsController
{
    /**
     * Lists all PersonTitle entities.
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $cache = $this->get('array_cache');
        $source = new Entity('OjsJournalBundle:PersonTitle');
        $source->manipulateRow(
            function (Row $row) use ($request, $cache) {
                /* @var PersonTitle $entity */
                $entity = $row->getEntity();
                if (!is_null($entity)) {
                    if($cache->contains('grid_row_id_'.$entity->getId())){
                        $row->setClass('hidden');
                    }else{
                        $entity->setDefaultLocale($request->getDefaultLocale());
                        $cache->save('grid_row_id_'.$entity->getId(), true);
                        $row->setField('translations.title', $entity->getTitleTranslations());
                    }
                }
                return $row;
            }
        );
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('ojs_admin_person_title_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_person_title_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_person_title_delete', 'id');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('OjsAdminBundle:AdminPersonTitle:index.html.twig', ['grid' => $grid]);
    }

    /**
     * Creates a new PersonTitle entity.
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $entity = new PersonTitle();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect(
                $this->generateUrl(
                    'ojs_admin_person_title_show',
                    array('id' => $entity->getId())
                )
            );
        }

        return $this->render(
            'OjsAdminBundle:AdminPersonTitle:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a PersonTitle entity.
     *
     * @param PersonTitle $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(PersonTitle $entity)
    {
        $form = $this->createForm(
            new PersonTitleType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_person_title_create'),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new PersonTitle entity.
     *
     */
    public function newAction()
    {
        $entity = new PersonTitle();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminPersonTitle:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a PersonTitle entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:PersonTitle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PersonTitle entity.');
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_person_title'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminPersonTitle:show.html.twig',
            array(
                'entity' => $entity,
                'token' => $token
            )
        );
    }

    /**
     * Displays a form to edit an existing PersonTitle entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:PersonTitle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PersonTitle entity.');
        }

        $editForm = $this->createEditForm($entity);

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_person_title'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminPersonTitle:edit.html.twig',
            array(
                'entity' => $entity,
                'token' => $token,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a PersonTitle entity.
     *
     * @param PersonTitle $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(PersonTitle $entity)
    {
        $form = $this->createForm(
            new PersonTitleType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_person_title_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing PersonTitle entity.
     *
     * @param Request $request
     * @param integer $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:PersonTitle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PersonTitle entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');
            return $this->redirect($this->generateUrl('ojs_admin_person_title_edit', array('id' => $id)));
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_person_title'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminPersonTitle:edit.html.twig',
            array(
                'entity' => $entity,
                'token' => $token,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a PersonTitle entity.
     *
     * @param Request $request
     * @param integer $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $deleteService = $this->get('ojs_core.delete.service');

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:PersonTitle')->find($id);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_person_title'.$entity->getId());

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token not found!");
        }
        $deleteService->check($entity);

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_admin_person_title_index');
    }
}
