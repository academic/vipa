<?php

namespace Ojstr\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojstr\JournalBundle\Entity\ArticleFile;
use Ojstr\JournalBundle\Form\ArticleFileType;
use Gedmo\Uploadable\FileInfo\FileInfoArray;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * ArticleFile controller.
 *
 */
class ArticleFileController extends Controller {

    /**
     * Lists all ArticleFile entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjstrJournalBundle:ArticleFile')->findAll();

        return $this->render('OjstrJournalBundle:ArticleFile:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new ArticleFile entity.
     *
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $file = new ArticleFile();
        $form = $this->createCreateForm($file);
        if (isset($_FILES['articlefile'])) {
            $form->handleRequest($request);
            $data = $form->getData();
            //$listener->addEntityFileInfo($file, $fileInfo);
            $listener = $this->get('stof_doctrine_extensions.listener.uploadable');
            $listener->addEntityFileInfo($file, new FileInfoArray($_FILES['articlefile']));
            $file->setArticleId($data->getArticleId());
            $em->persist($file);
        }

        $em->flush();
//        $form = $this->createCreateForm($entity);
//        $form->handleRequest($request);
//
//        if ($form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($entity);
//            $em->flush();
//
//            return $this->redirect($this->generateUrl('articlefile_show', array('id' => $entity->getId())));
//        } 

        return $this->redirect($this->generateUrl('articlefile_show', array('id' => $file->getId())));
    }

    /**
     * Creates a form to create a ArticleFile entity.
     *
     * @param ArticleFile $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ArticleFile $entity) {
        $form = $this->createForm(new ArticleFileType(), $entity, array(
            'action' => $this->generateUrl('articlefile_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ArticleFile entity.
     *
     */
    public function newAction($articleId = NULL) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Article')->find($articleId);
        if (empty($entity)) {
            throw new NotFoundHttpException('Article not found!');
        }
        $form = $this->createCreateForm(new ArticleFile());

        return $this->render('OjstrJournalBundle:ArticleFile:new.html.twig', array(
                    //'entity' => $entity,
                    'articleId' => $entity ? $entity->getId() : NULL,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ArticleFile entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrJournalBundle:ArticleFile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleFile entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrJournalBundle:ArticleFile:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing ArticleFile entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrJournalBundle:ArticleFile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleFile entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrJournalBundle:ArticleFile:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a ArticleFile entity.
     *
     * @param ArticleFile $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ArticleFile $entity) {
        $form = $this->createForm(new ArticleFileType(), $entity, array(
            'action' => $this->generateUrl('articlefile_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing ArticleFile entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrJournalBundle:ArticleFile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleFile entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('articlefile_edit', array('id' => $id)));
        }

        return $this->render('OjstrJournalBundle:ArticleFile:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ArticleFile entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjstrJournalBundle:ArticleFile')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ArticleFile entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('articlefile'));
    }

    /**
     * Creates a form to delete a ArticleFile entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('articlefile_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array(
                            'label' => $this->get('translator')->trans('Delete'),
                            'attr' => array('class' => 'btn btn-danger', 'onclick' => 'return confirm("' .
                                $this->get('translator')->trans('Are you sure?') . '"); ')
                        ))
                        ->getForm()
        ;
    }

}
