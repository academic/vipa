<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalDesign;
use Ojs\JournalBundle\Form\Type\JournalDesignType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;

/**
 * JournalDesign controller.
 *
 */
class JournalDesignController extends Controller
{

    /**
     * Lists all JournalDesign entities.
     *
     */
    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for view this journal's sections!");
        }
        $source = new Entity('OjsJournalBundle:JournalDesign');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('admin_journaldesign_show', 'id');
        $rowAction[] = $gridAction->editAction('admin_journaldesign_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('admin_journaldesign_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:JournalDesign:index.html.twig', $data);
    }

    /**
     * Creates a new JournalDesign entity.
     *
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for view this journal's sections!");
        }
        $entity = new JournalDesign();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('admin_journaldesign_show', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:JournalDesign:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a JournalDesign entity.
     *
     * @param JournalDesign $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(JournalDesign $entity)
    {
        $form = $this->createForm(
            new JournalDesignType(),
            $entity,
            array(
                'action' => $this->generateUrl('admin_journaldesign_create'),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new JournalDesign entity.
     *
     */
    public function newAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for view this journal's sections!");
        }
        $entity = new JournalDesign();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsJournalBundle:JournalDesign:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a JournalDesign entity.
     * @param  JournalDesign                              $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(JournalDesign $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for view this journal's sections!");
        }

        return $this->render(
            'OjsJournalBundle:JournalDesign:show.html.twig',
            array(
                'entity' => $entity,
            )
        );
    }

    /**
     * Displays a form to edit an existing JournalDesign entity.
     *
     * @param  JournalDesign                              $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(JournalDesign $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for view this journal's sections!");
        }
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsJournalBundle:JournalDesign:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a JournalDesign entity.
     *
     * @param JournalDesign $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(JournalDesign $entity)
    {
        $form = $this->createForm(
            new JournalDesignType(),
            $entity,
            array(
                'action' => $this->generateUrl('admin_journaldesign_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing JournalDesign entity.
     *
     * @param  Request                                                                                       $request
     * @param  JournalDesign                                                                                 $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, JournalDesign $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for view this journal's sections!");
        }
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('admin_journaldesign_edit', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:JournalDesign:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request                                            $request
     * @param  JournalDesign                                      $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws TokenNotFoundException
     */
    public function deleteAction(Request $request, JournalDesign $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('DELETE', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for view this journal's sections!");
        }
        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('admin_journaldesign'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('admin_JournalDesign');
    }
}
