<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\Common\Helper\FileHelper;
use Ojs\Common\Params\ArticleFileParams;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\ArticleFile;
use Ojs\JournalBundle\Entity\File;
use Ojs\JournalBundle\Form\Type\ArticleFileType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * ArticleFile controller.
 *
 */
class ArticleFileController extends Controller
{

    /**
     * Lists all ArticleFile entities.
     *
     * @param   Request     $request
     * @return  Response
     */
    public function indexAction(Request $request)
    {
        if ($request->query->get('article') == null)
            throw new NotFoundHttpException("Not found");

        $article = $this->getDoctrine()
            ->getRepository('OjsJournalBundle:Article')
            ->find($request->query->get('article'));

        $this->throw404IfNotFound($article);

        if (!$this->isGranted('VIEW', $article->getJournal(), 'articles') &&
            !$this->isGranted('VIEW', $article))
            throw new AccessDeniedException("You not authorized for this page!");

        $source = new Entity('OjsJournalBundle:ArticleFile');
        $tableAlias = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $qb) use ($article, $tableAlias) {
                return $qb
                    ->where($tableAlias . '.article = :article')
                    ->setParameter('article', $article);
            }
        );

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');
        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('ojs_journal_article_file_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_journal_article_file_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_journal_article_file_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $data = [];
        $data['grid'] = $grid;
        $data['article'] = $article;

        return $grid->getGridResponse('OjsJournalBundle:ArticleFile:index.html.twig', $data);
    }

    /**
     * Creates a new ArticleFile entity.
     *
     * @param  Request                   $request
     * @param  Article                   $article
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request, Article $article)
    {
        if (!$this->isGranted('EDIT', $article->getJournal(), 'articles') && !$this->isGranted('EDIT', $article)) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        $entity = new ArticleFile();
        $form = $this->createCreateForm($entity, $article);
        $form->handleRequest($request);
        $fileHelper = new FileHelper();
        $em = $this->getDoctrine()->getManager();

        $file_entity = new File();
        if ($form->isValid()) {
            $file = $request->request->get('file');
            $file_entity->setName($file);
            $imagePath = $this->get('kernel')->getRootDir().'/../web/uploads/articlefiles/'.$fileHelper->generatePath(
                    $file,
                    false
                );
            $file_entity->setSize(filesize($imagePath.$file));
            $file_entity->setMimeType(mime_content_type($imagePath.$file));
            $file_entity->setPath('/uploads/articlefiles/'.$fileHelper->generatePath($file, false));
            $em->persist($file_entity);

            $entity->setArticle($article);
            $entity->setFile($file_entity);
            $v = $form->getData()->getVersion();
            if (empty($v)) {
                $entity->setVersion(1);
            }
            $em->persist($entity);
            $article->addArticleFile($entity);
            $em->persist($article);

            $em->flush();

            $this->successFlashBag('Successfully created.');

            return $this->redirect($this->generateUrl('ojs_journal_article_file_index', array('article' => $article->getId())));
        }

        return $this->render(
            'OjsJournalBundle:ArticleFile:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a ArticleFile entity.
     *
     * @param   ArticleFile $entity
     * @param   Article     $article
     * @return  Form
     */
    private function createCreateForm(ArticleFile $entity, $article)
    {
        $form = $this->createForm(
            new ArticleFileType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_journal_article_file_create', ['article' => $article]),
                'method' => 'POST',
                'articlesEndPoint' => $this->generateUrl('api_get_articles', ['id' => $entity->getArticleId()]),
                'articleEndPoint' => $this->generateUrl('api_get_article', ['id' => $entity->getArticleId()]),
            )
        );

        return $form;
    }

    /**
     * Displays a form to create a new ArticleFile entity.
     *
     * @param  Article  $article
     * @return Response
     */
    public function newAction(Article $article)
    {
        if (!$this->isGranted('EDIT', $article->getJournal(), 'articles') && !$this->isGranted('EDIT', $article)) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        $entity = new ArticleFile();
        $entity->setArticle($article);
        $form = $this->createCreateForm($entity, $article->getId());

        return $this->render(
            'OjsJournalBundle:ArticleFile:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
                'article' => $article,
            )
        );
    }

    /**
     * Finds and displays a ArticleFile entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var ArticleFile $entity */
        $entity = $em->getRepository('OjsJournalBundle:ArticleFile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        if (!$this->isGranted('VIEW', $entity->getArticle()->getJournal(), 'articles') && !$this->isGranted(
                'VIEW',
                $entity->getArticle()
            )
        ) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $type = ArticleFileParams::fileType($entity->getType());

        return $this->render(
            'OjsJournalBundle:ArticleFile:show.html.twig',
            array(
                'entity' => $entity,
                'type' => $type,
            )
        );
    }

    /**
     * Displays a form to edit an existing ArticleFile entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var ArticleFile $entity */
        $entity = $em->getRepository('OjsJournalBundle:ArticleFile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        if (!$this->isGranted('EDIT', $entity->getArticle()->getJournal(), 'articles') && !$this->isGranted(
                'EDIT',
                $entity->getArticle()
            )
        ) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsJournalBundle:ArticleFile:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a ArticleFile entity.
     *
     * @param ArticleFile $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(ArticleFile $entity)
    {
        $form = $this->createForm(
            new ArticleFileType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_journal_article_file_update', ['id' => $entity->getId()]),
                'method' => 'PUT',
                'articlesEndPoint' => $this->generateUrl('api_get_articles', ['id' => $entity->getId()]),
                'articleEndPoint' => $this->generateUrl('api_get_article', ['id' => $entity->getId()]),
            )
        );

        return $form;
    }

    /**
     * Edits an existing ArticleFile entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var ArticleFile $entity */
        $entity = $em->getRepository('OjsJournalBundle:ArticleFile')->find($id);
        $file_entity = $entity->getFile();
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        if (!$this->isGranted('EDIT', $entity->getArticle()->getJournal(), 'articles') && !$this->isGranted(
                'EDIT',
                $entity->getArticle()
            )
        ) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        $fileHelper = new FileHelper();

        if ($editForm->isValid()) {
            $file = $request->request->get('file');
            $file_entity->setName($file);
            $file_entity->setName($file);
            $imagePath = $this->get('kernel')->getRootDir().'/../web/uploads/articlefiles/'.$fileHelper->generatePath(
                    $file,
                    false
                );
            $file_entity->setSize(filesize($imagePath.$file));
            $file_entity->setMimeType(mime_content_type($imagePath.$file));
            $file_entity->setPath('/uploads/articlefiles/'.$fileHelper->generatePath($file, false));
            $em->persist($file_entity);

            $em->flush();

            $this->successFlashBag('Successfully updated.');

            return $this->redirect($this->generateUrl('ojs_journal_article_file_edit', array('id' => $id)));
        }

        return $this->render(
            'OjsJournalBundle:ArticleFile:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a ArticleFile entity.
     *
     * @param  Request          $request
     * @param  ArticleFile      $entity
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, ArticleFile $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_journal_article_file_index'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        if (!$this->isGranted('EDIT', $entity->getArticle()->getJournal(), 'articles') && !$this->isGranted(
                'EDIT',
                $entity->getArticle()
            )
        ) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_journal_article_file_index', ['article' => $entity->getArticleId()]);
    }
}
