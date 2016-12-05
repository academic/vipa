<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Ojs\CoreBundle\Controller\OjsController;
use Ojs\CoreBundle\Helper\StringHelper;
use Ojs\JournalBundle\Entity\JournalFile;
use Ojs\JournalBundle\Form\Type\JournalFileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;

class JournalFileController extends OjsController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('VIEW', $journal, 'files')) {
            throw new AccessDeniedException("You are not authorized for this file!");
        }

        $router = $this->get('router');
        $source = new Entity('OjsJournalBundle:JournalFile');
        $source->manipulateRow(
            function (Row $row) use ($request,$router) {

                /* @var JournalFile $entity */
                $entity = $row->getEntity();
                $pathLinkTemplate = $this->getParameter('base_host').'/uploads/files/'.$entity->getPath();
                $row->setField('path', $pathLinkTemplate);
                
                return $row;
            }
        );
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('ojs_journal_filemanager_show', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->editAction('ojs_journal_filemanager_edit', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->deleteAction('ojs_journal_filemanager_delete', ['id', 'journalId' => $journal->getId()]);
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('OjsJournalBundle:JournalFile:index.html.twig', ['grid' => $grid]);
    }

    /**
     * Displays a form to create a new JournalFile entity.
     */
    public function newAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', $journal, 'files')) {
            throw new AccessDeniedException("You are not authorized for this file!");
        }

        $entity = new JournalFile();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsJournalBundle:JournalFile:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Creates a form to create a JournalFile entity.
     *
     * @param JournalFile $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(JournalFile $entity)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $form = $this->createForm(
            new JournalFileType(),
            $entity,
            [
                'action' => $this->generateUrl('ojs_journal_filemanager_create', ['journalId' => $journal->getId()]),
                'method' => 'POST'
            ]
        );
        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Creates a new JournalFile entity.
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', $journal, 'files')) {
            throw new AccessDeniedException("You are not authorized for this file!");
        }

        $entity = new JournalFile();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $rootDir = $this->getParameter('kernel.root_dir');
            $path = $rootDir . '/../web/uploads/files/' . $entity->getPath();
            $entity->setSize(StringHelper::formatBytes(filesize($path)));
            $entity->setJournal($journal);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('successful.create');
            return $this->redirectToRoute('ojs_journal_filemanager_show',
                ['id' => $entity->getId(), 'journalId' => $journal->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:JournalFile:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Finds and displays a JournalFile entity.
     *
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        /** @var JournalFile $entity */
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $entity = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:JournalFile')
            ->find($id);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('VIEW', $journal, 'files')) {
            throw new AccessDeniedException("You are not authorized for this file!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_filemanager'.$entity->getId());

        return $this->render(
            'OjsJournalBundle:JournalFile:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing Lang entity.
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id)
    {
        /** @var JournalFile $entity */
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $entity = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:JournalFile')
            ->find($id);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('EDIT', $journal, 'files')) {
            throw new AccessDeniedException("You are not authorized for this file!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_filemanager'.$entity->getId());

        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsJournalBundle:JournalFile:edit.html.twig',
            [
                'token' => $token,
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            ]
        );
    }

    /**
     * Creates a form to edit a Lang entity.
     *
     * @param  JournalFile $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(JournalFile $entity)
    {
        $form = $this->createForm(
            new JournalFileType(),
            $entity,
            [
                'action' => $this->generateUrl(
                    'ojs_journal_filemanager_update',
                    ['id' => $entity->getId(), 'journalId' => $entity->getJournal()->getId()]
                ),
                'method' => 'PUT',
            ]
        );

        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * Edits an existing Lang entity.
     * @param  Request $request
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $id)
    {
        /** @var JournalFile $entity */
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $entity = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:JournalFile')
            ->find($id);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('EDIT', $journal, 'files')) {
            throw new AccessDeniedException("You are not authorized for this file!");
        }

        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');
            return $this->redirectToRoute('ojs_journal_filemanager_edit',
                ['id' => $entity->getId(), 'journalId' => $journal->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:JournalFile:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request $request
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        /** @var JournalFile $entity */
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $entity = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:JournalFile')
            ->find($id);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('DELETE', $journal, 'files')) {
            throw new AccessDeniedException("You are not authorized for this file!");
        }

        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_journal_filemanager'.$entity->getId());

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token not found!");
        }
        $this->get('ojs_core.delete.service')->check($entity);
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_journal_filemanager_index', ['journalId' => $journal->getId()]);
    }
}
