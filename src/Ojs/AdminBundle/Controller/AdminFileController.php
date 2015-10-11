<?php

namespace Ojs\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ojs\AdminBundle\Entity\AdminFile;
use Ojs\CmsBundle\Form\Type\FileType;
use Symfony\Component\HttpFoundation\Response;

/**
 * AdminFile controller.
 */
class AdminFileController extends Controller
{
    /**
     * Lists all AdminFile entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjsAdminBundle:AdminFile')->findAll();

        return $this->render('OjsAdminBundle:AdminFile:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new AdminFile entity.
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $entity = new AdminFile();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ojs_admin_file_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsAdminBundle:AdminFile:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a AdminFile entity.
     *
     * @param AdminFile $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AdminFile $entity)
    {
        $form = $this->createForm(new FileType(), $entity, array(
            'action' => $this->generateUrl('ojs_admin_file_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new AdminFile entity.
     * @return Response
     */
    public function newAction()
    {
        $entity = new AdminFile();
        $form   = $this->createCreateForm($entity);

        return $this->render('OjsAdminBundle:AdminFile:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminFile entity.
     * @param int $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsAdminBundle:AdminFile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdminFile entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsAdminBundle:AdminFile:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminFile entity.
     * @param int $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsAdminBundle:AdminFile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdminFile entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsAdminBundle:AdminFile:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a AdminFile entity.
    *
    * @param AdminFile $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(AdminFile $entity)
    {
        $form = $this->createForm(new FileType(), $entity, array(
            'action' => $this->generateUrl('ojs_admin_file_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing AdminFile entity.
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsAdminBundle:AdminFile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdminFile entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ojs_admin_file_edit', array('id' => $id)));
        }

        return $this->render('OjsAdminBundle:AdminFile:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a AdminFile entity.
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjsAdminBundle:AdminFile')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find AdminFile entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ojs_admin_file_index'));
    }

    /**
     * Creates a form to delete a AdminFile entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ojs_admin_file_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
