<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\ArticleAuthor;
use Ojs\JournalBundle\Entity\Author;
use Ojs\JournalBundle\Form\Type\ArticleAuthorType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ArticleAuthor controller.
 *
 */
class ArticleAuthorController extends Controller
{
    /**
     * Lists all ArticleAuthor entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsJournalBundle:ArticleAuthor')->findAll();

        return $this->render(
            'OjsJournalBundle:ArticleAuthor:index.html.twig',
            array(
                'entities' => $entities,
            )
        );
    }

    /**
     * Creates a new ArticleAuthor entity.
     *
     * @param  Request          $request
     * @param  Integer          $articleId
     * @return RedirectResponse
     */
    public function createAction(Request $request, $articleId)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $entity = new ArticleAuthor();
        $form = $this->createCreateForm($entity, $articleId);
        $data = $request->request->get($form->getName());
        $em = $this->getDoctrine()->getManager();
        /** @var Article $article */
        $article = $em->getRepository('OjsJournalBundle:Article')->find($data['article']);
        /** @var Author $author */
        $author = $em->getRepository('OjsJournalBundle:Author')->find($data['author']);
        $entity->setArticle($article);
        $entity->setAuthor($author);
        $entity->setAuthorOrder($data['authorOrder']);
        $em->persist($entity);
        $em->flush();
        $this->successFlashBag('successful.create');

        return $this->redirect($this->generateUrl('ojs_journal_article_author_show', array('id' => $entity->getId(), 'journalId' => $journal->getId(), 'articleId' => $articleId)));
    }

    /**
     * Creates a form to create a ArticleAuthor entity.
     *
     * @param ArticleAuthor $entity The entity
     * @param Integer $articleId
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ArticleAuthor $entity, $articleId)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $form = $this->createForm(
            new ArticleAuthorType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_journal_article_author_create', ['articleId'=> $articleId, 'journalId'=> $journal->getId()]),
                'method' => 'POST',
                'journal' => $journal,
            )
        );
        $form->add('submit', 'submit', array('label' => 'Create New'));

        return $form;
    }

    /**
     * Displays a form to create a new ArticleAuthor entity.
     *
     * @param Integer $articleId
     */
    public function newAction($articleId)
    {
        $entity = new ArticleAuthor();
        $form = $this->createCreateForm($entity, $articleId);

        return $this->render(
            'OjsJournalBundle:ArticleAuthor:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
                'articleId' => $articleId,
            )
        );
    }

    /**
     * Finds and displays a ArticleAuthor entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:ArticleAuthor')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        return $this->render(
            'OjsJournalBundle:ArticleAuthor:show.html.twig',
            array(
                'entity' => $entity,
            )
        );
    }

    /**
     * Displays a form to edit an existing ArticleAuthor entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var ArticleAuthor $entity */
        $entity = $em->getRepository('OjsJournalBundle:ArticleAuthor')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsJournalBundle:ArticleAuthor:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a ArticleAuthor entity.
     *
     * @param ArticleAuthor $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(ArticleAuthor $entity)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        $form = $this->createForm(
            new ArticleAuthorType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_journal_article_author_update', array('id' => $entity->getId(), 'journalId' => $journal->getId(), 'articleId' => $entity->getArticleId())),
                'method' => 'PUT',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing ArticleAuthor entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        /* @var $entity ArticleAuthor */
        $entity = $em->getRepository('OjsJournalBundle:ArticleAuthor')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        $editForm = $this->createEditForm($entity);
        //$editForm->handleRequest($request);
        $data = $request->request->get($editForm->getName());
        /** @var Article $article */
        $article = $em->getRepository('OjsJournalBundle:Article')->find($data['article']);
        /** @var Author $author */
        $author = $em->getRepository('OjsJournalBundle:Author')->find($data['author']);
        $authorOrder = $data['authorOrder'];
        if ($article && $author) {
            $entity->setArticle($article);
            $entity->setAuthor($author);
            $entity->setAuthorOrder($authorOrder);
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('successful.update');

            return $this->redirect($this->generateUrl('ojs_journal_article_author_edit', array('id' => $id, 'journalId' => $journal->getId(), 'articleId' => $data['article'])));
        }

        return $this->render(
            'OjsJournalBundle:ArticleAuthor:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a ArticleAuthor entity.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction($id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:ArticleAuthor')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirect($this->generateUrl('ojs_journal_article_author_index', array('id' => $id, 'journalId' => $journal->getId(), 'articleId' => $entity->getArticleId())));
    }
}
