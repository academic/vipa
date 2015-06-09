<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Symfony\Component\HttpFoundation\Request;
use Ojs\JournalBundle\Entity\Lang;
use Ojs\JournalBundle\Form\LangType;
use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
        if(!$this->isGranted('VIEW', new Lang())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $source = new Entity('OjsJournalBundle:Lang');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('lang_show', 'id');
        $rowAction[] = $gridAction->editAction('lang_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('lang_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:Lang:index.html.twig', $data);
    }

    /**
     * Creates a new Lang entity.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        if(!$this->isGranted('VIEW', new Lang())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new Lang();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('lang_show', ['id' => $entity->getId()]);
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
     * @param Lang $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Lang $entity)
    {
        $this->throw404IfNotFound($entity);
        if(!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        return $this->render('OjsJournalBundle:Lang:show.html.twig', array(
                    'entity' => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing Lang entity.
     * @param Lang $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Lang $entity)
    {
        $this->throw404IfNotFound($entity);
        if(!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity);
        return $this->render('OjsJournalBundle:Lang:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
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
     * @param Request $request
     * @param Lang $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, Lang $entity)
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

            return $this->redirectToRoute('lang_edit', ['id' => $entity->getId()]);
        }

        return $this->render('OjsJournalBundle:Lang:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param Lang $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Lang $entity)
    {
        $this->throw404IfNotFound($entity);
        if(!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('lang'.$entity->getId());
        if($token!=$request->get('_token'))
            throw new TokenNotFoundException("Token Not Found!");
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('lang');
    }
}
