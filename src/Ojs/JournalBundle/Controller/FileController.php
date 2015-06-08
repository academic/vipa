<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Helper\ActionHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\File;
use Ojs\JournalBundle\Form\FileType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * File controller.
 *
 */
class FileController extends Controller
{
    /**
     * Lists all File entities.
     *
     */
    public function indexAction()
    {
        if(!$this->isGranted('VIEW', new File())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $source = new Entity('OjsJournalBundle:File');
        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        ActionHelper::setup($this->get('security.csrf.token_manager'), $this->get('translator'));

        $rowAction[] = ActionHelper::showAction('admin_file_show', 'id');
        $rowAction[] = ActionHelper::editAction('admin_file_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('admin_file_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;
        return $grid->getGridResponse('OjsJournalBundle:File:index.html.twig', $data);
    }

    /**
     * Creates a new File entity.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        if(!$this->isGranted('CREATE', new File())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $file = new File();
        $form = $this->createCreateForm($file);
        $form->handleRequest($request);
        $em->persist($file);
        $em->flush();
//        $form = $this->createCreateForm($entity);
//        $form->handleRequest($request);
//
//        if ($form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($entity);
//            $em->flush();
//
//            return $this->redirect($this->generateUrl('admin_file_show', array('id' => $entity->getId())));
//        }
        $this->successFlashBag('successful.create');
        return $this->redirectToRoute('admin_file_show', ['id' => $file->getId()]);
    }

    /**
     * Creates a form to create a File entity.
     *
     * @param File $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(File $entity)
    {
        $form = $this->createForm(new FileType(), $entity, array(
            'action' => $this->generateUrl('admin_file_create'),
            'apiRoot' => $this->generateUrl('ojs_api_homepage'),
            'method' => 'POST',
        ));


        return $form;
    }

    /**
     * Displays a form to create a new File entity.
     *
     */
    public function newAction()
    {
        if(!$this->isGranted('CREATE', new File())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $form = $this->createCreateForm(new File());

        return $this->render('OjsJournalBundle:File:new.html.twig', array(
            //'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a File entity.
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:File')->find($id);
        if(!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        return $this->render('OjsJournalBundle:File:show.html.twig', array(
            'entity' => $entity));
    }

    /**
     * Displays a form to edit an existing File entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:File')->find($id);
        if(!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:File:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a File entity.
     *
     * @param File $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(File $entity)
    {
        $form = $this->createForm(new FileType(), $entity, array(
            'action' => $this->generateUrl('admin_file_update', array('id' => $entity->getId())),
            'apiRoot' => $this->generateUrl('ojs_api_homepage'),
            'method' => 'POST',
        ));


        return $form;
    }

    /**
     * Edits an existing File entity.
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:File')->find($id);
        if(!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        $em->persist($entity);

        $em->flush();
        $this->successFlashBag('successful.update');
        return $this->redirectToRoute('admin_file_edit', ['id' => $id]);

    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:File')->find($id);
        if(!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('admin_file' . $id);
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');
        return $this->redirect($this->generateUrl('admin_file'));
    }

}
