<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\CitationSetting;
use Ojs\JournalBundle\Form\CitationSettingType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * CitationSetting controller.
 *
 */
class CitationSettingController extends Controller
{
    /**
     * Lists all CitationSetting entities.
     *
     * @return Response
     */
    public function indexAction()
    {
        if(!$this->isGranted('VIEW', new CitationSetting())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $source = new Entity('OjsJournalBundle:CitationSetting');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');
        
        $rowAction[] = $gridAction->showAction('citationsetting_show', 'id');
        $rowAction[] = $gridAction->editAction('citationsetting_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('citationsetting_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:CitationSetting:index.html.twig', $data);

    }

    /**
     * Creates a new CitationSetting entity.
     *
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        if(!$this->isGranted('CREATE', new CitationSetting())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new CitationSetting();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('citationsetting_show', ['id' => $entity->getId()]);
        }

        return $this->render('OjsJournalBundle:CitationSetting:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a CitationSetting entity.
     *
     * @param CitationSetting $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CitationSetting $entity)
    {
        $form = $this->createForm(new CitationSettingType(), $entity, array(
            'action' => $this->generateUrl('citationsetting_create'),
            'method' => 'POST',
            'citationsEndPoint' => $this->generateUrl('api_get_citations'),
            'citationEndPoint' => $this->generateUrl('api_get_citation'),
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CitationSetting entity.
     *
     */
    public function newAction()
    {
        if(!$this->isGranted('CREATE', new CitationSetting())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new CitationSetting();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:CitationSetting:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CitationSetting entity.
     *
     * @param CitationSetting $entity
     * @return Response
     */
    public function showAction(CitationSetting $entity)
    {
        $this->throw404IfNotFound($entity);
        if(!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        return $this->render('OjsJournalBundle:CitationSetting:show.html.twig', array(
            'entity' => $entity,));
    }

    /**
     * Displays a form to edit an existing CitationSetting entity.
     *
     * @param CitationSetting $entity
     * @return Response
     */
    public function editAction(CitationSetting $entity)
    {
        $this->throw404IfNotFound($entity);
        if(!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity);
        return $this->render('OjsJournalBundle:CitationSetting:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a CitationSetting entity.
     *
     * @param CitationSetting $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(CitationSetting $entity)
    {
        $form = $this->createForm(new CitationSettingType(), $entity, array(
            'action' => $this->generateUrl('citationsetting_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'citationsEndPoint' => $this->generateUrl('api_get_citations'),
            'citationEndPoint' => $this->generateUrl('api_get_citation'),
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing CitationSetting entity.
     *
     * @param Request $request
     * @param CitationSetting $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, CitationSetting $entity)
    {
        $this->throw404IfNotFound($entity);
        if(!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('citationsetting_edit', ['id' => $entity->getId()]);
        }
        return $this->render('OjsJournalBundle:CitationSetting:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param CitationSetting $entity
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, CitationSetting $entity)
    {
        $this->throw404IfNotFound($entity);
        if(!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $form = $this->createDeleteForm($entity->getId());
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('citationsetting'.$entity->getId());
        if($token!=$request->get('_token'))
            throw new TokenNotFoundException("Token Not Found!");

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirect($this->generateUrl('citationsetting'));
    }

    /**
     * @param $id
     * @return Form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('citationsetting_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
