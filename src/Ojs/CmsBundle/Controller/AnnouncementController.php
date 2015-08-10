<?php

namespace Ojs\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ojs\CmsBundle\Entity\Announcement;
use Ojs\CmsBundle\Form\AnnouncementType;

/**
 * Announcement controller.
 *
 */
class AnnouncementController extends Controller
{

    /**
     * Lists all Announcement entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjsCmsBundle:Announcement')->findAll();

        return $this->render('OjsCmsBundle:Announcement:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Announcement entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Announcement();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('announcement_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsCmsBundle:Announcement:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Announcement entity.
     *
     * @param Announcement $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Announcement $entity)
    {
        $form = $this->createForm(new AnnouncementType(), $entity, array(
            'action' => $this->generateUrl('announcement_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Announcement entity.
     *
     */
    public function newAction()
    {
        $entity = new Announcement();
        $form   = $this->createCreateForm($entity);

        return $this->render('OjsCmsBundle:Announcement:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Announcement entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsCmsBundle:Announcement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Announcement entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsCmsBundle:Announcement:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Announcement entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsCmsBundle:Announcement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Announcement entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsCmsBundle:Announcement:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Announcement entity.
    *
    * @param Announcement $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Announcement $entity)
    {
        $form = $this->createForm(new AnnouncementType(), $entity, array(
            'action' => $this->generateUrl('announcement_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Announcement entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsCmsBundle:Announcement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Announcement entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('announcement_edit', array('id' => $id)));
        }

        return $this->render('OjsCmsBundle:Announcement:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Announcement entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjsCmsBundle:Announcement')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Announcement entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('announcement'));
    }

    /**
     * Creates a form to delete a Announcement entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('announcement_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
