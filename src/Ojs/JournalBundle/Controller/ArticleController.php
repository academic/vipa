<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Form\Type\ArticleType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;

/**
 * Article controller
 */
class ArticleController extends Controller
{
    /**
     * Lists all article entities for journal
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $this->throw404IfNotFound($journal);

        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $source = new Entity('OjsJournalBundle:Article');
        $source->manipulateRow(
            function (Row $row) use ($request)
            {
                /** @var Article $entity */
                $entity = $row->getEntity();
                $entity->setDefaultLocale($request->getDefaultLocale());
                if(!is_null($entity)){
                    $row->setField('title', $entity->getTitle());
                    if(!is_null($entity->getIssue())){
                        $row->setField('issue', $entity->getIssue()->getTitle());
                    }
                }
                return $row;
            }
        );

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');
        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('ojs_journal_article_show', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->editAction('ojs_journal_article_edit', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->deleteAction(
            'ojs_journal_article_delete',
            ['id', 'journalId' => $journal->getId()]
        );
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse(
            'OjsJournalBundle:Article:index.html.twig',
            ['journal' => $journal]
        );
    }

    /**
     * Displays a form to create a new article entity
     *
     * @return Response
     */
    public function newAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $this->throw404IfNotFound($journal);

        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        $entity = new Article();
        $form = $this->createCreateForm($entity, $journal);

        return $this->render(
            'OjsJournalBundle:Article:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Creates a form to create a Article entity.
     *
     * @param  Article $entity The entity
     * @param  Journal $journal
     * @return Form    The form
     */
    private function createCreateForm(Article $entity, Journal $journal)
    {
        $form = $this->createForm(
            new ArticleType(),
            $entity,
            [
                'action' => $this->generateUrl('ojs_journal_article_create', ['journalId' => $journal->getId()]),
                'method' => 'POST',
                'journal' => $journal,
            ]
        );
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Creates a new Article entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $entity = new Article();
        $entity = $entity->setJournal($journal);

        $form = $this->createCreateForm($entity, $journal);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setCurrentLocale($request->getDefaultLocale());
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('ojs_journal_article_show', ['id' => $entity->getId(), 'journalId'=> $entity->getJournal()->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:Article:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Finds and displays an article entity
     *
     * @param  int      $id
     * @return Response
     */
    public function showAction($id)
    {
        /* @var $entity Article */
        /* @var Journal $journal */
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        $em = $this->getDoctrine()->getManager();

        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        $entity = $em->getRepository('OjsJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_article'.$entity->getId());

        return $this->render('OjsJournalBundle:Article:show.html.twig', ['entity' => $entity, 'token' => $token]);
    }

    /**
     * Displays a form to edit an existing article entity
     *
     * @param  int      $id
     * @return Response
     */
    public function editAction($id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        /** @var Article $entity */
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);

        $editForm = $this->createEditForm($entity, $journal)
            ->add('update', 'submit', array('label' => 'u'));

        if (!$this->isGranted('EDIT', $entity->getJournal(), 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        return $this->render(
            'OjsJournalBundle:Article:edit.html.twig',
            ['entity' => $entity, 'form' => $editForm->createView()]
        );
    }

    /**
     * Creates a form to edit a Article entity.
     *
     * @param  Article $entity The entity
     * @param  Journal $journal
     * @return Form    The form
     */
    private function createEditForm(Article $entity, Journal $journal)
    {
        $action = $this->generateUrl(
            'ojs_journal_article_update',
            ['id' => $entity->getId(), 'journalId' => $journal->getId()]
        );
        $form = $this->createForm(
            new ArticleType(),
            $entity,
            ['action' => $action, 'method' => 'PUT', 'journal' => $journal]
        );

        return $form;
    }

    /**
     * Edits an existing Article entity.
     *
     * @param  Request                   $request
     * @param  int                       $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        /** @var Article $entity */
        $em = $this->getDoctrine()->getManager();

        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $entity = $em->getRepository('OjsJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);

        $editForm = $this->createEditForm($entity, $journal)
            ->add('update', 'submit', array('label' => 'u'));

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect(
                $this->generateUrl('ojs_journal_article_edit', array('id' => $id, 'journalId' => $journal->getId()))
            );
        }

        return $this->render(
            'OjsJournalBundle:Article:edit.html.twig',
            ['entity' => $entity, 'form' => $editForm->createView()]
        );
    }

    /**
     * Deletes an article entity
     *
     * @param  Request          $request
     * @param  int              $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        $em = $this->getDoctrine()->getManager();

        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        /** @var Article $entity */
        $entity = $em->getRepository('OjsJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_journal_article'.$id);

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirect($this->generateUrl('ojs_journal_article_index', ['journalId' => $journal->getId()]));
    }
}
