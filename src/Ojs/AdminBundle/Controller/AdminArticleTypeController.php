<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\ArticleTypes;
use Ojs\JournalBundle\Form\Type\ArticleTypesType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * ArticleTypes controller.
 *
 */
class AdminArticleTypeController extends Controller
{
    /**
     * Lists all ArticleTypes entities.
     *
     * @return Response
     */
    public function indexAction()
    {
        if (!$this->isGranted('VIEW', new ArticleTypes())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $source = new Entity('OjsJournalBundle:ArticleTypes');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('ojs_admin_article_type_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_article_type_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_article_type_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsAdminBundle:AdminArticleType:index.html.twig', $data);
    }

    /**
     * Creates a new ArticleTypes entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new ArticleTypes())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new ArticleTypes();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirect($this->generateUrl('ojs_admin_article_type_show', array('id' => $entity->getId())));
        }

        return $this->render(
            'OjsAdminBundle:AdminArticleType:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a ArticleTypes entity.
     *
     * @param ArticleTypes $entity
     *
     * @return Form The form
     */
    private function createCreateForm(ArticleTypes $entity)
    {
        $form = $this->createForm(
            new ArticleTypesType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_article_type_create'),
                'method' => 'POST',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ArticleTypes entity.
     *
     * @return Response
     */
    public function newAction()
    {
        if (!$this->isGranted('CREATE', new ArticleTypes())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new ArticleTypes();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminArticleType:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a ArticleTypes entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:ArticleTypes')->find($id);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);

        return $this->render(
            'OjsAdminBundle:AdminArticleType:show.html.twig',
            array(
                'entity' => $entity,
            )
        );
    }

    /**
     * Displays a form to edit an existing ArticleTypes entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:ArticleTypes')->find($id);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminArticleType:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a ArticleTypes entity.
     *
     * @param ArticleTypes $entity
     *
     * @return Form The form
     */
    private function createEditForm(ArticleTypes $entity)
    {
        $form = $this->createForm(
            new ArticleTypesType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_article_type_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing ArticleTypes entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:ArticleTypes')->find($id);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect($this->generateUrl('ojs_admin_article_type_edit', array('id' => $id)));
        }

        return $this->render(
            'OjsAdminBundle:AdminArticleType:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a ArticleTypes entity.
     *
     * @param  Request          $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:ArticleTypes')->find($id);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_article_type'.$id);
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirect($this->generateUrl('ojs_admin_article_type_index'));
    }
}
