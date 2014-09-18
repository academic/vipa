<?php

namespace Ojstr\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojstr\JournalBundle\Entity\ArticleAuthor;
use Ojstr\JournalBundle\Form\ArticleAuthorType;

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
        $entities = $em->getRepository('OjstrJournalBundle:ArticleAuthor')->findAll();

        return $this->render('OjstrJournalBundle:ArticleAuthor:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new ArticleAuthor entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ArticleAuthor();
        $form = $this->createCreateForm($entity);
        $data = $request->request->get($form->getName());
        $em = $this->getDoctrine()->getManager();
        $entity->setArticle(
                $em->getRepository('OjstrJournalBundle:Article')->find($data['articleId'])
        );
        $entity->setAuthor(
                $em->getRepository('OjstrJournalBundle:Author')->find($data['authorId'])
        );
        $entity->setAuthorOrder($data['authorOrder']);
        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('articleauthor_show', array('id' => $entity->getId())));
    }

    /**
     * Creates a form to create a ArticleAuthor entity.
     *
     * @param ArticleAuthor $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ArticleAuthor $entity)
    {
        $form = $this->createForm(new ArticleAuthorType(), $entity, array(
            'action' => $this->generateUrl('articleauthor_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create New'));

        return $form;
    }

    /**
     * Displays a form to create a new ArticleAuthor entity.
     *
     */
    public function newAction()
    {
        $entity = new ArticleAuthor();
        $form = $this->createCreateForm($entity);

        return $this->render('OjstrJournalBundle:ArticleAuthor:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ArticleAuthor entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:ArticleAuthor')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleAuthor entity.');
        }

        return $this->render('OjstrJournalBundle:ArticleAuthor:show.html.twig', array(
                    'entity' => $entity
        ));
    }

    /**
     * Displays a form to edit an existing ArticleAuthor entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:ArticleAuthor')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleAuthor entity.');
        }
        $editForm = $this->createEditForm($entity);

        return $this->render('OjstrJournalBundle:ArticleAuthor:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Creates a form to edit a ArticleAuthor entity.
     *
     * @param ArticleAuthor $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ArticleAuthor $entity)
    {
        $form = $this->createForm(new ArticleAuthorType(), $entity, array(
            'action' => $this->generateUrl('articleauthor_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing ArticleAuthor entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $entity ArticleAuthor */
        $entity = $em->getRepository('OjstrJournalBundle:ArticleAuthor')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleAuthor entity.');
        }
        $editForm = $this->createEditForm($entity);
        //$editForm->handleRequest($request);
        $data = $request->request->get($editForm->getName());
        $articleId = $data['articleId'];
        $authorId = $data['authorId'];
        $authorOrder = $data['authorOrder'];
        if ($articleId && $authorId) {
            $entity->setArticle(
                    $em->getRepository('OjstrJournalBundle:Article')->find($articleId)
            );
            $entity->setAuthor(
                    $em->getRepository('OjstrJournalBundle:Author')->find($authorId)
            );
            $entity->setAuthorOrder($authorOrder);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('articleauthor_edit', array('id' => $id)));
        }

        return $this->render('OjstrJournalBundle:ArticleAuthor:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a ArticleAuthor entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:ArticleAuthor')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleAuthor entity.');
        }
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('articleauthor'));
    }

}
