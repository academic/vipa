<?php

namespace Vipa\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\JournalBundle\Entity\Issue;
use Vipa\JournalBundle\Entity\IssueFile;
use Vipa\JournalBundle\Form\Type\IssueFileType;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * IssueFile controller.
 */
class IssueFileController extends Controller
{
    public function indexAction(Request $request, $issueId)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        $this->throw404IfNotFound($journal);

        if (!$this->isGranted('VIEW', $journal, 'issues')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $source = new Entity('VipaJournalBundle:IssueFile');
        $source->manipulateRow(
            function (Row $row) use ($request)
            {
                /* @var IssueFile $entity */
                $entity = $row->getEntity();
                $entity->setDefaultLocale($request->getDefaultLocale());
                if(!is_null($entity)){
                    $row->setField('title', $entity->getTitle());
                    $row->setField('description', $entity->getDescription());
                }
                return $row;
            }
        );

        /** @var Issue $issue */
        $issue = $em->getRepository('VipaJournalBundle:Issue')->find($issueId);
        $this->throw404IfNotFound($issue);

        $alias = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $query) use ($alias, $issue) {
                $query
                    ->andWhere($alias.'.issue = :issue')
                    ->setParameter('issue', $issue);
            }
        );

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');
        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('vipa_journal_issue_file_show', ['id', 'journalId' => $journal->getId(), 'issueId' => $issueId]);
        $rowAction[] = $gridAction->editAction('vipa_journal_issue_file_edit', ['id', 'journalId' => $journal->getId(), 'issueId' => $issueId]);
        $rowAction[] = $gridAction->deleteAction('vipa_journal_issue_file_delete', ['id', 'journalId' => $journal->getId(), 'issueId' => $issueId]);
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $data = [];
        $data['grid'] = $grid;
        $data['issue'] = $issue;

        return $grid->getGridResponse('VipaJournalBundle:IssueFile:index.html.twig', $data);
    }

    /**
     * Creates a new IssueFile entity.
     *
     * @param Request $request
     * @param $issueId
     * @return RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     */
    public function createAction(Request $request, $issueId)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        if (!$this->isGranted('CREATE', $journal, 'issues')) {
            throw new AccessDeniedException('You are not authorized for create  issue file for this journal!');
        }
        /** @var Issue $issue */
        $issue = $em->getRepository('VipaJournalBundle:Issue')->find($issueId);
        $this->throw404IfNotFound($issue);

        $entity = new IssueFile();
        $entity->setIssue($issue);
        $form = $this->createCreateForm($entity, $journal->getId())
            ->add('create', 'submit', array('label' => 'c'));

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $entity->setIssue($issue);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('vipa_journal_issue_file_edit',
                ['id' => $entity->getId(), 'journalId' => $journal->getId(), 'issueId' => $issue->getId()]));
        }

        return $this->render('VipaJournalBundle:IssueFile:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param IssueFile $entity
     * @param $journalId
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(IssueFile $entity, $journalId)
    {
        $form = $this->createForm(new IssueFileType(), $entity, [
            'action' => $this->generateUrl('vipa_journal_issue_file_create', array('journalId' => $journalId, 'issueId' => $entity->getIssue()->getId())),
            'method' => 'POST',
        ]);

        return $form;
    }

    /**
     * Displays a form to create a new IssueFile entity.
     *
     * @param $issueId
     * @return Response
     */
    public function newAction($issueId)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        if (!$this->isGranted('CREATE', $journal, 'issues')) {
            throw new AccessDeniedException('You are not authorized for create  issue file for this journal!');
        }
        /** @var Issue $issue */
        $issue = $em->getRepository('VipaJournalBundle:Issue')->find($issueId);
        $this->throw404IfNotFound($issue);

        $entity = new IssueFile();
        $entity->setIssue($issue);

        $form = $this->createCreateForm($entity, $journal->getId())
            ->add('create', 'submit', array('label' => 'c'));

        return $this->render('VipaJournalBundle:IssueFile:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a IssueFile entity.
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var IssueFile $entity */
        $entity = $em->getRepository('VipaJournalBundle:IssueFile')->find($id);

        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'issues')) {
            throw new AccessDeniedException('You are not authorized for edit this  issue file!');
        }
        /** @var Issue $issue */
        $issue = $em->getRepository('VipaJournalBundle:Issue')->find($entity->getIssue()->getId());
        $this->throw404IfNotFound($issue);

        $this->throw404IfNotFound($entity);

        $entity->setDefaultLocale($request->getDefaultLocale());
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_journal_issue_file'.$entity->getId());

        return $this->render('VipaJournalBundle:IssueFile:show.html.twig', array(
            'entity' => $entity,
            'token'  => $token,
        ));
    }

    /**
     * Displays a form to edit an existing IssueFile entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var IssueFile $entity */
        $entity = $em->getRepository('VipaJournalBundle:IssueFile')->find($id);

        $journal = $this->get('vipa.journal_service')->getSelectedJournal();

        if (!$this->isGranted('EDIT', $journal, 'issues')) {
            throw new AccessDeniedException('You are not authorized for edit this  issue file!');
        }
        /** @var Issue $issue */
        $issue = $em->getRepository('VipaJournalBundle:Issue')->find($entity->getIssue()->getId());
        $this->throw404IfNotFound($issue);

        $this->throw404IfNotFound($entity);

        $editForm = $this->createEditForm($entity)
            ->add('edit', 'submit', array('label' => 'e'));

        return $this->render('VipaJournalBundle:IssueFile:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a IssueFile entity.
     *
     * @param IssueFile $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(IssueFile $entity)
    {
        $form = $this->createForm(new IssueFileType(), $entity, [
            'action' => $this->generateUrl('vipa_journal_issue_file_update', ['id' => $entity->getId(), 'journalId' => $entity->getIssue()->getJournal()->getId(), 'issueId' => $entity->getIssue()->getId()]),
            'method' => 'PUT',
        ]);

        return $form;
    }

    /**
     * Edits an existing IssueFile entity.
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var IssueFile $entity */
        $entity = $em->getRepository('VipaJournalBundle:IssueFile')->find($id);

        $journal = $this->get('vipa.journal_service')->getSelectedJournal();

        if (!$this->isGranted('EDIT', $journal, 'issues')) {
            throw new AccessDeniedException('You are not authorized for edit this issue file!');
        }

        /** @var Issue $issue */
        $issue = $em->getRepository('VipaJournalBundle:Issue')->find($entity->getIssue()->getId());
        $this->throw404IfNotFound($issue);
        $this->throw404IfNotFound($entity);

        $editForm = $this->createEditForm($entity)
            ->add('edit', 'submit', array('label' => 'e'));
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('vipa_journal_issue_file_edit', array('id' => $id, 'journalId' => $journal->getId(), 'issueId' => $entity->getIssue()->getId())));
        }

        return $this->render('VipaJournalBundle:IssueFile:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a IssueFile entity.
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var IssueFile $entity */
        $entity = $em->getRepository('VipaJournalBundle:IssueFile')->find($id);
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();

        if (!$this->isGranted('DELETE', $journal, 'issues')) {
            throw new AccessDeniedException('You are not authorized for delete this issue file!');
        }
        /** @var Issue $issue */
        $issue = $em->getRepository('VipaJournalBundle:Issue')->find($entity->getIssue()->getId());
        $this->throw404IfNotFound($issue);

        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('vipa_journal_issue_file' . $id);
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $this->get('vipa_core.delete.service')->check($entity);

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');
        return $this->redirect($this->generateUrl('vipa_journal_issue_file_index', ['journalId' => $journal->getId(), 'issueId' => $entity->getIssue()->getId()]));
    }
}
