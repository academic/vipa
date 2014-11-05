<?php

namespace Ojs\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ojs\JournalBundle\Entity\Lang;
use Ojs\JournalBundle\Form\LangType;

/**
 * Lang controller.
 *
 */
class LangController extends Controller
{

    /**
     * Lists all Lang entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjsJournalBundle:Lang')->findAll();

        return $this->render('OjsJournalBundle:Lang:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Lang entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Lang();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('lang_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsJournalBundle:Lang:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Lang entity.
     *
     * @param Lang $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Lang $entity)
    {
        $form = $this->createForm(new LangType(), $entity, array(
            'action' => $this->generateUrl('lang_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Lang entity.
     *
     */
    public function newAction()
    {
        $entity = new Lang();
        $form   = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:Lang:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Lang entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:Lang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lang entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsJournalBundle:Lang:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Lang entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:Lang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lang entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsJournalBundle:Lang:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Lang entity.
    *
    * @param Lang $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Lang $entity)
    {
        $form = $this->createForm(new LangType(), $entity, array(
            'action' => $this->generateUrl('lang_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Lang entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:Lang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lang entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('lang_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:Lang:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Lang entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjsJournalBundle:Lang')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Lang entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('lang'));
    }

    /**
     * Creates a form to delete a Lang entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('lang_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
