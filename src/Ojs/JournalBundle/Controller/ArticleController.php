<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\Common\Helper\FileHelper;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\ArticleFile;
use Ojs\JournalBundle\Entity\File;
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
     * @return Response
     */
    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $this->throw404IfNotFound($journal);

        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $source = new Entity('OjsJournalBundle:Article');
        $source->addHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');
        $alias = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $query) use ($alias, $journal) {
                $query
                    ->andWhere($alias.'.journal = :journal')
                    ->setParameter('journal', $journal);
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

            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('ojs_journal_article_file_index', ['article' => $entity->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:Article:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Creates a form to create a Article entity.
     *
     * @param  Article $entity  The entity
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

        return $form;
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
        $entity = $em->getRepository('OjsJournalBundle:Article')->findOneBy(
            array('journal' => $journal, 'id' => $id)
        );
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

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
        $entity = $em->getRepository('OjsJournalBundle:Article')->findOneBy(
            array('journal' => $journal, 'id' => $id)
        );
        $this->throw404IfNotFound($entity);

        $editForm = $this->createEditForm($entity, $journal);

        if (!$this->isGranted('EDIT', $entity->getJournal(), 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        return $this->render(
            'OjsJournalBundle:Article:edit.html.twig',
            ['entity' => $entity, 'form' => $editForm->createView()]
        );
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
        $entity = $em->getRepository('OjsJournalBundle:Article')->findOneBy(
            array('journal' => $journal, 'id' => $id)
        );
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('EDIT', $entity->getJournal(), 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $editForm = $this->createEditForm($entity, $journal);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $files = $request->request->get('articlefiles', []);
            $fileHelper = new FileHelper();

            foreach ($files as $file) {
                $file_entity = new File();
                $file_entity->setName($file);

                $imagepath =
                    $this->get('kernel')->getRootDir().
                    '/../web/uploads/articlefiles/'.
                    $fileHelper->generatePath($file, false);

                $file_entity->setSize(filesize($imagepath.$file));
                $file_entity->setMimeType(mime_content_type($imagepath.$file));
                $file_entity->setPath('/uploads/articlefiles/'.$fileHelper->generatePath($file, false));
                $em->persist($file_entity);

                $articleFile = new ArticleFile();
                $articleFile->setArticle($entity);
                $articleFile->setFile($file_entity);
                $articleFile->setType(0); // TODO: See ArticleFileParams::$FILE_TYPES
                $articleFile->setVersion(1);
                $articleFile->setLangCode('tr'); // TODO: Don't use hardcoded locale
                $em->persist($articleFile);

                $entity->addArticleFile($articleFile);
            }

            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect(
                $this->generateUrl('ojs_journal_article_edit', array('id' => $id, 'journalId' => $journal->getId()))
            );
        }

        return $this->render(
            'OjsJournalBundle:Article:edit.html.twig',
            ['entity' => $entity, 'edit_form' => $editForm->createView()]
        );
    }

    /**
     * Creates a form to edit a Article entity.
     *
     * @param  Article $entity  The entity
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

        /** @var Article $entity */
        $entity = $em->getRepository('OjsJournalBundle:Article')->findOneBy(
            array('journal' => $journal, 'id' => $id)
        );
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('DELETE', $entity->getJournal(), 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

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
