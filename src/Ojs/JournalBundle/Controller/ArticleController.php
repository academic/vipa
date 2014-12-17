<?php

namespace Ojs\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Form\ArticleType;

/**
 * Article controller.
 *
 */
class ArticleController extends Controller
{

    public function citationAction($id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('OjsJournalBundle:Article')->find($id);
        $post = Request::createFromGlobals();
        if ($post->request->has('cites')) {
            echo "<pre>";
            print_r(json_decode($_POST['cites']));
            exit();
        } else {
            
        }

        return $this->render('OjsJournalBundle:Article:citation.html.twig', array(
                    'item' => $article,
                    'citationTypes' => $this->container->getParameter('citation_types')
        ));
    }

    /**
     * Lists all Article entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsJournalBundle:Article')->findAll();

        return $this->render('OjsJournalBundle:Article:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Lists all Article entities for journal
     * @param integer $journalId
     */
    public function indexJournalAction($journalId)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($journalId);
        $this->throw404IfNotFound($journal);
        $entities = $em->getRepository('OjsJournalBundle:Article')->findByJournalId($journalId);

        return $this->render('OjsJournalBundle:Article:index_journal.html.twig', array(
                    'entities' => $entities,
                    'journal' => $journal
        ));
    }

    /**
     * Lists all Article entities for issue
     * @param integer $journalId
     */
    public function indexIssueAction($issueId)
    {
        $em = $this->getDoctrine()->getManager();
        $issue = $em->getRepository('OjsJournalBundle:Issue')->find($issueId);
        $this->throw404IfNotFound($issue);
        $entities = $em->getRepository('OjsJournalBundle:Article')->findByIssueId($issueId);

        return $this->render('OjsJournalBundle:Article:index_issue.html.twig', array(
                    'entities' => $entities,
                    'issue' => $issue
        ));
    }

    /**
     * Creates a new Article entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Article();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('article_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsJournalBundle:Article:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Article entity.
     *
     * @param Article $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Article $entity)
    {
        $journal = $this->get('session')->get("selectedJournalId");
        $form = $this->createForm(
                new ArticleType(), $entity, array(
            'action' => $this->generateUrl('article_create'),
            'method' => 'POST',
            'journal' => $journal
            ,
            'user' => $this->getUser()
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Article entity.
     *
     */
    public function newAction()
    {
        $entity = new Article();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:Article:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays an Article entity for admin user
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $entity \Ojs\JournalBundle\Entity\Article  */
        $entity = $em->getRepository('OjsJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);

        return $this->render('OjsJournalBundle:Article:show.html.twig', array(
                    'entity' => $entity));
    }

    /**
     * Display an Article entity as author preview 
     * @param integer $id
     */
    public function previewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $entity \Ojs\JournalBundle\Entity\Article  */
        $entity = $em->getRepository('OjsJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);

        return $this->render('OjsJournalBundle:Article:author_preview.html.twig', array(
                    'entity' => $entity
        ));
    }

    /**
     * Displays a form to edit an existing Article entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:Article:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Creates a form to edit a Article entity.
     *
     * @param Article $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Article $entity)
    {
        $journal = $this->get('session')->get("selectedJournalId");

        $form = $this->createForm(new ArticleType(), $entity, array(
            'action' => $this->generateUrl('article_update', array('id' => $entity->getId())),
            'method' => 'POST',
            'journal' => $journal,
            'user' => $this->getUser()
        ));

        return $form;
    }

    /**
     * Edits an existing Article entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('article_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:Article:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a Article entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('article'));
    }

}
