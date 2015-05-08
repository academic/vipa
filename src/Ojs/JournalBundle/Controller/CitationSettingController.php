<?php

namespace Ojs\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\CitationSetting;
use Ojs\JournalBundle\Form\CitationSettingType;

/**
 * CitationSetting controller.
 *
 */
class CitationSettingController extends Controller
{
    /**
     * Lists all CitationSetting entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsJournalBundle:CitationSetting')->findAll();

        return $this->render('OjsJournalBundle:CitationSetting:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new CitationSetting entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CitationSetting();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');
            return $this->redirectToRoute('citationsetting_show', ['id' => $entity->getId()]);
        }

        return $this->render('OjsJournalBundle:CitationSetting:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a CitationSetting entity.
     *
     * @param CitationSetting $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CitationSetting $entity)
    {
        $form = $this->createForm(new CitationSettingType(), $entity, array(
            'action' => $this->generateUrl('citationsetting_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CitationSetting entity.
     *
     */
    public function newAction()
    {
        $entity = new CitationSetting();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:CitationSetting:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CitationSetting entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:CitationSetting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        return $this->render('OjsJournalBundle:CitationSetting:show.html.twig', array(
                    'entity' => $entity, ));
    }

    /**
     * Displays a form to edit an existing CitationSetting entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:CitationSetting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:CitationSetting:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a CitationSetting entity.
     *
     * @param CitationSetting $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CitationSetting $entity)
    {
        $form = $this->createForm(new CitationSettingType(), $entity, array(
            'action' => $this->generateUrl('citationsetting_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing CitationSetting entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:CitationSetting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');
            return $this->redirectToRoute('citationsetting_edit', ['id' => $id]);
        }

        return $this->render('OjsJournalBundle:CitationSetting:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a CitationSetting entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjsJournalBundle:CitationSetting')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('notFound');
            }

            $em->remove($entity);
            $em->flush();
        }
        $this->successFlashBag('successful.remove');
        return $this->redirect($this->generateUrl('citationsetting'));
    }

}
