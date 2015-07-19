<?php

namespace Ojs\JournalBundle\Controller;

use Doctrine\ORM\Query;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Citation;
use Ojs\JournalBundle\Form\Type\CitationType;

/**
 * Citation controller.
 *
 */
class CitationController extends Controller
{

    /**
     * Lists all Citation entities.
     *
     * @param   Request $request
     * @param   Integer $articleId
     * @return  Response
     */
    public function indexAction(Request $request, $articleId)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $this->throw404IfNotFound($journal);

        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $source = new Entity('OjsJournalBundle:Citation');
        $source->addHint(Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');

        if($articleId != null) {
            $alias = $source->getTableAlias();
            $source->manipulateQuery(
                function (QueryBuilder $query) use ($alias, $articleId) {
                    $query
                        ->join($alias.'.articles', 'a')
                        ->where('a.id = :articleId')
                        ->setParameter('articleId', $articleId);
                }
            );
        }

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');
        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('ojs_journal_citation_show', ['id', 'journalId' => $journal->getId(), 'articleId' => $articleId]);
        $rowAction[] = $gridAction->editAction('ojs_journal_citation_edit', ['id', 'journalId' => $journal->getId(), 'articleId' => $articleId]);
        $rowAction[] = $gridAction->deleteAction('ojs_journal_citation_delete', ['id', 'journalId' => $journal->getId(), 'articleId' => $articleId]);
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('OjsJournalBundle:Citation:index.html.twig');
    }
    /**
     * Creates a new Citation entity.
     * @param   Integer $articleId
     *
     */
    public function createAction(Request $request, $articleId)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $this->throw404IfNotFound($journal);

        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $entity = new Citation();
        $form = $this->createCreateForm($entity, $articleId);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ojs_journal_citation_show', array('id' => $entity->getId(), 'journalId' => $journal->getId(), 'articleId' => $articleId)));
        }

        return $this->render('OjsJournalBundle:Citation:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Citation entity.
     *
     * @param Citation $entity The entity
     * @param Integer $articleId
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Citation $entity, $articleId)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $form = $this->createForm(new CitationType(), $entity, array(
            'action' => $this->generateUrl('ojs_journal_citation_create', array('journalId' => $journal->getId(), 'articleId' => $articleId)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Citation entity.
     * @param   Integer $articleId
     *
     */
    public function newAction($articleId)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $this->throw404IfNotFound($journal);

        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $entity = new Citation();
        $form   = $this->createCreateForm($entity, $articleId);

        return $this->render('OjsJournalBundle:Citation:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Citation entity.
     *
     */
    public function showAction($id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $this->throw404IfNotFound($journal);

        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Citation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Citation entity.');
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_citation'.$entity->getId());

        return $this->render('OjsJournalBundle:Citation:show.html.twig', array(
            'entity'      => $entity,
            'token'       => $token,
        ));
    }

    /**
     * Displays a form to edit an existing Citation entity.
     * @param   Integer $articleId
     *
     */
    public function editAction($id, $articleId)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $this->throw404IfNotFound($journal);

        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Citation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Citation entity.');
        }

        $editForm = $this->createEditForm($entity, $articleId);

        return $this->render('OjsJournalBundle:Citation:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Citation entity.
    *
    * @param Citation $entity The entity
    * @param Integer $articleId
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Citation $entity, $articleId)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $form = $this->createForm(new CitationType(), $entity, array(
            'action' => $this->generateUrl('ojs_journal_citation_update', array('id' => $entity->getId(), 'journalId' => $journal->getId(), 'articleId' => $articleId)),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Citation entity.
     *
     * @param   Integer $articleId
     */
    public function updateAction(Request $request, $id, $articleId)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $this->throw404IfNotFound($journal);

        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Citation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Citation entity.');
        }

        $editForm = $this->createEditForm($entity, $articleId);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ojs_journal_citation_edit', array('id' => $id, 'journalId' => $journal->getId(), 'articleId' => $articleId)));
        }

        return $this->render('OjsJournalBundle:Citation:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Citation entity.
     *
     * @param   Integer $articleId
     */
    public function deleteAction(Request $request, $id, $articleId)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $this->throw404IfNotFound($journal);

        // Because when we delete a citation, that means we are editing an article.
        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Citation')->find($id);
        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_journal_citation'.$id);

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirect($this->generateUrl('ojs_journal_citation_index', array('journalId' => $journal->getId(), 'articleId' => $articleId)));
    }
}
