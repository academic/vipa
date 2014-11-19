<?php

namespace Ojs\JournalBundle\Controller\ArticleSubmission;

use Ojs\Common\Params\ArticleFileParams;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Form\ArticleType;
use Ojs\Common\Helper\CommonFormHelper as CommonFormHelper;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Article Submission controller.
 *
 */
class ArticleSubmissionController extends Controller
{

    /**
     * Lists all new Article submissions entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsJournalBundle:Article')->findBy(array('status' => 0));

        return $this->render('OjsJournalBundle:ArticleSubmission:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Lists all new Article submissions entities.
     *
     */
    public function indexAllAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsJournalBundle:Article')->findBy(array('status' => 0));

        return $this->render('OjsJournalBundle:ArticleSubmission:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Displays a form to create a new Article entity.
     * 
     * @param int $submissionId 
     */
    public function newAction()
    {
        $session = new Session();
        $selectedJournalId = $session->get('selectedJournalId');
        if (!$selectedJournalId) {
            return $this->render('::mustselectjournal.html.twig');
        }
        $entity = new Article();
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($selectedJournalId);

        return $this->render('OjsJournalBundle:ArticleSubmission:new.html.twig', array(
                    'articleId' => NULL,
                    'entity' => $entity,
                    'journal' => $journal,
                    'fileTypes' => ArticleFileParams::$FILE_TYPES,
                    'citationTypes' => $this->container->getParameter('citation_types')
        ));
    }

    public function resumeAction($submissionId)
    {
        $em = $this->getDoctrine()->getManager();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $articleSubmission = $dm->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->find($submissionId);
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($articleSubmission->getJournalId());
        return $this->render('OjsJournalBundle:ArticleSubmission:new.html.twig', array(
                    'submissionId' => $articleSubmission->getId(),
                    'submissionData' => $articleSubmission,
                    'journal' => $journal,
                    'fileTypes' => ArticleFileParams::$FILE_TYPES,
                    'citationTypes' => $this->container->getParameter('citation_types')
        ));
    }

    /**
     * Finds and displays a Article entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $entity \Ojs\JournalBundle\Entity\Article */
        $entity = $em->getRepository('OjsJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);

        return $this->render('OjsJournalBundle:Article:show.html.twig', array(
                    'entity' => $entity));
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
        $form = $this->createForm(new ArticleType(), $entity, array(
            'action' => $this->generateUrl('article_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));

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

    /**
     * Creates a form to delete a Article entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        $formHelper = new CommonFormHelper();

        return $formHelper->createDeleteForm($this, $id, 'article_delete');
    }

}
