<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Ojs\JournalBundle\Entity\Issue;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\IssueFile;
use Ojs\JournalBundle\Form\Type\IssueFileType;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * IssueFile controller.
 */
class IssueFileController extends Controller
{
    public function indexAction(Request $request, $issueId)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $this->throw404IfNotFound($journal);

        if (!$this->isGranted('VIEW', $journal, 'issues')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $source = new Entity('OjsJournalBundle:IssueFile');
        $source->addHint(Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');

        $issue = $this->getDoctrine()->getRepository('OjsJournalBundle:Issue')->find($issueId);
        $this->throw404IfNotFound($issue);

        $alias = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $query) use ($alias, $issueId) {
                $query
                    ->join($alias.'.issue', 'i')
                    ->where('i.id = :issueId')
                    ->setParameter('issueId', $issueId);
            }
        );

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');
        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('ojs_journal_issue_file_show', ['id', 'journalId' => $journal->getId(), 'issueId' => $issueId]);
        $rowAction[] = $gridAction->editAction('ojs_journal_issue_file_edit', ['id', 'journalId' => $journal->getId(), 'issueId' => $issueId]);
        $rowAction[] = $gridAction->deleteAction('ojs_journal_issue_file_delete', ['id', 'journalId' => $journal->getId(), 'issueId' => $issueId]);
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $data = [];
        $data['grid'] = $grid;
        $data['issue'] = $issue;

        return $grid->getGridResponse('OjsJournalBundle:IssueFile:index.html.twig', $data);
    }

    /**
     * Creates a new IssueFile entity.
     */
    public function createAction(Request $request, $issueId)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', $journal, 'issues')) {
            throw new AccessDeniedException('You are not authorized for create  issue file for this journal!');
        }

        $entity = new IssueFile();
        $entity->setIssueId($issueId);
        $form = $this->createCreateForm($entity, $journal->getId());
        $form->submit($request);

        if ($form->isValid()) {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $issue = $em->getReference('OjsJournalBundle:Issue', $entity->getIssueId());
            $entity->setIssue($issue);
            $entity->setTranslatableLocale($request->getDefaultLocale());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ojs_journal_issue_file_edit',
                ['id' => $entity->getId(), 'journalId' => $journal->getId(), 'issueId' => $entity->getIssueId()]));
        }

        return $this->render('OjsJournalBundle:IssueFile:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a IssueFile entity.
     *
     * @param IssueFile $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(IssueFile $entity, $journalId)
    {
        $languages = $this->container->getParameter('languages');
        $langs = [];
        foreach ($languages as $key => $lang) {
            $langs[$lang['code']] = $lang['name'];
        }

        $form = $this->createForm(new IssueFileType(), $entity, [
            'action' => $this->generateUrl('ojs_journal_issue_file_create', array('journalId' => $journalId, 'issueId' => $entity->getIssueId())),
            'method' => 'POST',
            'languages' => $langs,
        ]);

        return $form;
    }

    /**
     * Displays a form to create a new IssueFile entity.
     */
    public function newAction(Request $request, $issueId)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', $journal, 'issues')) {
            throw new AccessDeniedException('You are not authorized for create  issue file for this journal!');
        }
        $entity = new IssueFile();
        $entity->setIssueId($issueId);

        $form = $this->createCreateForm($entity, $journal->getId());

        return $this->render('OjsJournalBundle:IssueFile:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a IssueFile entity.
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:IssueFile')->find($id);

        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'issues')) {
            throw new AccessDeniedException('You are not authorized for edit this  issue file!');
        }

        $this->throw404IfNotFound($entity);

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_issue_file'.$entity->getId());

        return $this->render('OjsJournalBundle:IssueFile:show.html.twig', array(
            'entity' => $entity,
            'token'  => $token,
        ));
    }

    /**
     * Displays a form to edit an existing IssueFile entity.
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:IssueFile')->find($id);

        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('EDIT', $journal, 'issues')) {
            throw new AccessDeniedException('You are not authorized for edit this  issue file!');
        }

        $this->throw404IfNotFound($entity);

        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:IssueFile:edit.html.twig', array(
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
        $languages = $this->container->getParameter('languages');
        $langs = [];
        foreach ($languages as $key => $lang) {
            $langs[$lang['code']] = $lang['name'];
        }
        $form = $this->createForm(new IssueFileType(), $entity, [
            'action' => $this->generateUrl('ojs_journal_issue_file_update', ['id' => $entity->getId(), 'journalId' => $entity->getIssue()->getJournalId(), 'issueId' => $entity->getIssueId()]),
            'method' => 'PUT',
            'languages' => $langs,
        ]);

        return $form;
    }

    /**
     * Edits an existing IssueFile entity.
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:IssueFile')->find($id);

        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('EDIT', $journal, 'issues')) {
            throw new AccessDeniedException('You are not authorized for edit this issue file!');
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find IssueFile entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ojs_journal_issue_file_edit', array('id' => $id, 'journalId' => $journal->getId(), 'issueId' => $entity->getIssueId())));
        }

        return $this->render('OjsJournalBundle:IssueFile:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a IssueFile entity.
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:IssueFile')->find($id);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('DELETE', $journal, 'issues')) {
            throw new AccessDeniedException('You are not authorized for delete this issue file!');
        }
        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_journal_issue_file' . $id);
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');
        return $this->redirect($this->generateUrl('ojs_journal_issue_file_index', ['journalId' => $journal->getId(), 'issueId' => $entity->getIssueId()]));
    }
}
