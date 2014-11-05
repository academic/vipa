<?php

namespace Ojstr\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojstr\JournalBundle\Entity\Institution;
use Ojstr\JournalBundle\Form\InstitutionType;

/**
 * Institution controller.
 *
 */
class InstitutionController extends Controller
{
    /**
     * Lists all Institution entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjstrJournalBundle:Institution')->findAll();

        return $this->render('OjstrJournalBundle:Institution:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new Institution entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Institution();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('institution_show', array('id' => $entity->getId())));
        }

        return $this->render('OjstrJournalBundle:Institution:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Institution entity.
     *
     * @param Institution $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Institution $entity)
    {
        $form = $this->createForm(new InstitutionType(), $entity, array(
            'action' => $this->generateUrl('institution_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Institution entity.
     *
     */
    public function newAction()
    {
        $entity = new Institution();
        $form = $this->createCreateForm($entity);

        return $this->render('OjstrJournalBundle:Institution:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Institution entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Institution')->find($id);
        $this->throw404IfNotFound($entity);

        return $this->render('OjstrJournalBundle:Institution:show.html.twig', array(
                    'entity' => $entity,));
    }

    /**
     * Displays a form to edit an existing Institution entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Institution')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render('OjstrJournalBundle:Institution:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Institution entity.
     *
     * @param Institution $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Institution $entity)
    {
        $form = $this->createForm(new InstitutionType(), $entity, array(
            'action' => $this->generateUrl('institution_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Institution entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Institution')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('institution_edit', array('id' => $id)));
        }

        return $this->render('OjstrJournalBundle:Institution:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Institution entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Institution')->find($id);
        $this->throw404IfNotFound($entity);
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('institution'));
    }

}
