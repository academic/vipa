<?php

namespace Ojs\JournalBundle\Controller;

use Doctrine\ORM\Query;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Author;
use Ojs\JournalBundle\Form\Type\AuthorType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * Author controller.
 *
 */
class AuthorController extends Controller
{
    /**
     * Lists all Author entities.
     *
     */
    public function indexAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('VIEW', new Author())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $source = new Entity('OjsJournalBundle:Author');
        $source->manipulateRow(
            function ($row) use ($request)
            {
                /**
                 * @var \APY\DataGridBundle\Grid\Row $row
                 * @var Author $entity
                 */
                $entity = $row->getEntity();
                $entity->setDefaultLocale($request->getDefaultLocale());
                if(!is_null($entity)){
                    $row->setField('title', $entity->getTitle());
                }
                return $row;
            }
        );
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('ojs_journal_author_show', ['id', 'journalId' => $journal->getId()]);
        if ($this->isGranted('EDIT', new Author())) {
            $rowAction[] = $gridAction->editAction('ojs_journal_author_edit', ['id', 'journalId' => $journal->getId()]);
        }
        if ($this->isGranted('DELETE', new Author())) {
            $rowAction[] = $gridAction->deleteAction('ojs_journal_author_delete', ['id', 'journalId' => $journal->getId()]);
        }

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:Author:index.html.twig', $data);
    }

    /**
     * Creates a new Author entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', new Author())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new Author();
        $form = $this->createCreateForm($entity, $journal->getId());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirect($this->generateUrl('ojs_journal_author_show', array('id' => $entity->getId(), 'journalId' => $journal->getId())));
        }

        return $this->render(
            'OjsJournalBundle:Author:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Author entity.
     *
     * @param Author $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Author $entity, $journalId)
    {
        $form = $this->createForm(
            new AuthorType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_journal_author_create', ['journalId' => $journalId]),
                'method' => 'POST',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Author entity.
     *
     * @return Response
     */
    public function newAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', new Author())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new Author();
        $form = $this->createCreateForm($entity, $journal->getId());

        return $this->render(
            'OjsJournalBundle:Author:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Author entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Author')->find($id);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_author'.$entity->getId());

        return $this->render('OjsJournalBundle:Author:show.html.twig', ['entity' => $entity, 'token' => $token]);
    }

    /**
     * Displays a form to edit an existing Author entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        $em = $this->getDoctrine()->getManager();
        /** @var Author $entity */
        $entity = $em->getRepository('OjsJournalBundle:Author')->find($id);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity, $journal->getId());

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_author'.$entity->getId());

        return $this->render(
            'OjsJournalBundle:Author:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'token' => $token,
            )
        );
    }

    /**
     * Creates a form to edit a Author entity.
     *
     * @param Author $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Author $entity, $journalId)
    {
        $form = $this->createForm(
            new AuthorType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_journal_author_update', array('id' => $entity->getId(), 'journalId' => $journalId)),
                'method' => 'PUT',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Author entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        $em = $this->getDoctrine()->getManager();
        /** @var Author $entity */
        $entity = $em->getRepository('OjsJournalBundle:Author')->find($id);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity, $journal->getId());
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect($this->generateUrl('ojs_journal_author_edit', array('id' => $id, 'journalId' => $journal->getId())));
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_author'.$entity->getId());

        return $this->render(
            'OjsJournalBundle:Author:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'token' => $token,
            )
        );
    }

    /**
     * Deletes a Author entity.
     *
     * @param  Request          $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Author')->find($id);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_journal_author'.$id);
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirect($this->generateUrl('ojs_journal_author_index', array('journalId' => $journal->getId())));
    }
}
