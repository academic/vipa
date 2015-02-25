<?php

namespace Ojs\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojs\JournalBundle\Entity\MailTemplate;
use Ojs\JournalBundle\Form\MailTemplateType;
use Symfony\Component\Yaml\Parser;
use Ojs\JournalBundle\Form\MailTemplateAltType;

/**
 * MailTemplate controller.
 *
 */
class MailTemplateController extends Controller {

    /**
     * Lists all MailTemplate entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $isAdmin = $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        if ($isAdmin) {
            $entities = $em->getRepository('OjsJournalBundle:MailTemplate')->findAll();
        } else {
            $journal = $this->get('ojs.journal_service')->getSelectedJournal();
            $entities = $em->getRepository('OjsJournalBundle:MailTemplate')->findByJournalId($journal->getId());
        }
        $yamlParser = new Parser();
        $defaultTemplates = $yamlParser->parse(file_get_contents(
                        $this->container->getParameter('kernel.root_dir') .
                        '/../src/Ojs/JournalBundle/Resources/data/mailtemplates.yml'
        ));
        return $this->render('OjsJournalBundle:MailTemplate:' . ($isAdmin ? 'admin/' : '') . 'index.html.twig', array(
                    'entities' => $entities,
                    'defaultTemplate' => $defaultTemplates
        ));
    }

    /**
     * Creates a new MailTemplate entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new MailTemplate();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $isAdmin = $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN');

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $isAdmin = $this->get('security.context')->isGranted('ROLE_SUPEr_ADMIN');
            return $this->redirect($this->generateUrl('mailtemplate' . ($isAdmin ? '' : '_manager') . '_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsJournalBundle:MailTemplate:' . ($isAdmin ? 'admin/' : '') . 'new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a MailTemplate entity.
     *
     * @param MailTemplate $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(MailTemplate $entity) {
        if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $form = $this->createForm(new MailTemplateType(), $entity, array(
                'action' => $this->generateUrl('mailtemplate_create'),
                'method' => 'POST',
            ));
        } else {
            $journal = $this->get('ojs.journal_service')->getSelectedJournal();
            $form = $this->createForm(new MailTemplateAltType(), $entity, [
                'method' => 'POST',
                'action' => $this->get('router')->generate('mailtemplate_manager_create'),
                'journal_id' => $journal->getId()
                    ]
            );
        }

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new MailTemplate entity.
     *
     */
    public function newAction() {
        $entity = new MailTemplate();
        $form = $this->createCreateForm($entity);
        $isAdmin = $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN');

        return $this->render('OjsJournalBundle:MailTemplate:' . ($isAdmin ? 'admin/' : '') . 'new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a MailTemplate entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $isAdmin = $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN');

        $entity = $em->getRepository('OjsJournalBundle:MailTemplate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailTemplate entity.');
        }

        return $this->render('OjsJournalBundle:MailTemplate:' . ($isAdmin ? 'admin/' : '') . 'show.html.twig', array(
                    'entity' => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing MailTemplate entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();
        $isAdmin = $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN');

        $entity = $em->getRepository('OjsJournalBundle:MailTemplate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailTemplate entity.');
        }

        $editForm = $this->createEditForm($entity);
        return $this->render('OjsJournalBundle:MailTemplate:' . ($isAdmin ? 'admin/' : '') . 'edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a MailTemplate entity.
     *
     * @param MailTemplate $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(MailTemplate $entity) {
        $isAdmin = $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        if ($isAdmin) {
            $form = $this->createForm(new MailTemplateType(), $entity, array(
                'action' => $this->generateUrl('mailtemplate_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            ));
        } else {
            $journal = $this->get('ojs.journal_service')->getSelectedJournal();
            $form = $this->createForm(new MailTemplateAltType(), $entity, array(
                'action' => $this->generateUrl('mailtemplate_manager_update', array('id' => $entity->getId())),
                'method' => 'PUT',
                'journal_id'=>$journal->getId()
            ));
        }

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing MailTemplate entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:MailTemplate')->find($id);
        $isAdmin = $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailTemplate entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl($isAdmin ? 'mailtemplate_edit' : 'mailtemplate_manager_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:MailTemplate:' . ($isAdmin ? 'admin/' : '') . 'edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a MailTemplate entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:MailTemplate')->find($id);
        $isAdmin = $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN');

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailTemplate entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl($isAdmin ? 'mailtemplate' : 'mailtemplate_manager'));
    }

}
