<?php

namespace Ojs\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Citation;
use Ojs\JournalBundle\Form\CitationType;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Helper\ActionHelper;

/**
 * Citation controller.
 *
 */
class CitationController extends Controller
{
    /**
     * Lists all Citation entities.
     *
     */
    public function indexAction()
    {
         $source = new Entity('OjsJournalBundle:Citation');
        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = ActionHelper::showAction('citation_show', 'id');
        $rowAction[] = ActionHelper::editAction('citation_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('citation_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn); 
        return $grid->getGridResponse('OjsJournalBundle:Citation:index.html.twig', array('grid'=>$grid));

       // $entities = $em->getRepository('OjsJournalBundle:Citation')->findAll();
 
    }

    /**
     * Creates a new Citation entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Citation();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('citation_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsJournalBundle:Citation:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Citation entity.
     *
     * @param Citation $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Citation $entity)
    {
        $form = $this->createForm(new CitationType(), $entity, array(
            'action' => $this->generateUrl('citation_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Citation entity.
     *
     */
    public function newAction()
    {
        $entity = new Citation();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:Citation:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Citation for an Article
     *
     */
    public function articleAction($article_id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $article = !empty($article_id) ?
                $em->getRepository('OjsJournalBundle:Article')->find($article_id) :
                null;
        $entity = new Citation();
        $entity->addArticle($article);
        $form = $this->createForm(new \Ojs\JournalBundle\Form\ArticleCitationType(), $entity);

        return $this->render('OjsJournalBundle:Citation:article.html.twig', array(
                    'entity' => $entity,
                    'article' => $article,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Citation entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:Citation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        return $this->render('OjsJournalBundle:Citation:show.html.twig', array(
                    'entity' => $entity));
    }

    /**
     * Displays a form to edit an existing Citation entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Citation')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:Citation:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Creates a form to edit a Citation entity.
     *
     * @param  Citation                     $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Citation $entity)
    {
        $form = $this->createForm(new CitationType(), $entity, array(
            'action' => $this->generateUrl('citation_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Citation entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Citation')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('citation_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:Citation:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a Citation entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Citation')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('citation'));
    }

}
