<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalSubmissionFile;
use Ojs\JournalBundle\Event\JournalEvents;
use Ojs\JournalBundle\Form\Type\JournalSubmissionFileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Ojs\JournalBundle\Event\JournalEvent;

/**
 * JournalSubmissionFile controller.
 *
 */
class JournalSubmissionFileController extends Controller
{
    /**
     * Lists all SubmissionFile entities.
     *
     */
    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'file')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        if (!$journal) {
            throw new NotFoundHttpException("Journal not found!");
        }
        $source = new Entity('OjsJournalBundle:JournalSubmissionFile');

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('ojs_journal_file_show', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->editAction('ojs_journal_file_edit', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->deleteAction('ojs_journal_file_delete', ['id', 'journalId' => $journal->getId()]);

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;
        $data['journal_id'] = $journal;

        return $grid->getGridResponse('OjsJournalBundle:SubmissionFile:index.html.twig', $data);
    }

    /**
     * Creates a new SubmissionFile entity.
     *
     * @param  Request                                                                                       $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'file')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $entity = new JournalSubmissionFile();
        $form = $this->createCreateForm($entity, $journal->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setJournal($journal);
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            $event = new JournalEvent($request, $journal, $this->getUser(), 'create');
            $dispatcher->dispatch(JournalEvents::JOURNAL_SUBMISSION_FILES_CHANGE, $event);

            return $this->redirect(
                $this->generateUrl('ojs_journal_file_show', array('id' => $entity->getId(), 'journalId' => $journal->getId()))
            );
        }

        return $this->render(
            'OjsJournalBundle:SubmissionFile:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @param JournalSubmissionFile $entity
     * @param $journalId
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(JournalSubmissionFile $entity, $journalId)
    {
        $languages = [];
        if (is_array($this->container->getParameter('languages'))) {
            foreach ($this->container->getParameter('languages') as $key => $language) {
                if (array_key_exists('code', $language)) {
                    $languages[$language['code']] = $language['name'];
                }
            }
        }
        $form = $this->createForm(
            new JournalSubmissionFileType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_journal_file_create', ['journalId' => $journalId]),
                'languages' => $languages,
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new SubmissionFile entity.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'file')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $entity = new JournalSubmissionFile();
        $form = $this->createCreateForm($entity, $journal->getId());

        return $this->render(
            'OjsJournalBundle:SubmissionFile:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a SubmissionFile entity.
     *
     * @param  JournalSubmissionFile                        $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(JournalSubmissionFile $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'file')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_file'.$entity->getId());

        return $this->render(
            'OjsJournalBundle:SubmissionFile:show.html.twig',
            array(
                'entity' => $entity,
                'token'  => $token,
            )
        );
    }

    /**
     * Displays a form to edit an existing SubmissionFile entity.
     *
     * @param  JournalSubmissionFile                        $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(JournalSubmissionFile $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'file')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsJournalBundle:SubmissionFile:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a SubmissionFile entity.
     *
     * @param JournalSubmissionFile $entity
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm(JournalSubmissionFile $entity)
    {
        $languages = [];
        if (is_array($this->container->getParameter('languages'))) {
            foreach ($this->container->getParameter('languages') as $key => $language) {
                if (array_key_exists('code', $language)) {
                    $languages[$language['code']] = $language['name'];
                }
            }
        }

        $form = $this->createForm(
            new JournalSubmissionFileType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_journal_file_update', array('id' => $entity->getId(), 'journalId' => $entity->getJournal()->getId())),
                'languages' => $languages,
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing SubmissionFile entity.
     *
     * @param  Request                                                                                       $request
     * @param  JournalSubmissionFile                                                                           $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, JournalSubmissionFile $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'file')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            $event = new JournalEvent($request, $journal, $this->getUser(), 'update');
            $dispatcher->dispatch(JournalEvents::JOURNAL_SUBMISSION_FILES_CHANGE, $event);

            return $this->redirect(
                $this->generateUrl('ojs_journal_file_edit', array('id' => $entity->getId(), 'journalId' => $journal->getId()))
            );
        }

        return $this->render(
            'OjsJournalBundle:SubmissionFile:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a SubmissionFile entity.
     *
     * @param  Request                                            $request
     * @param  JournalSubmissionFile                                $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, JournalSubmissionFile $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('DELETE', $journal, 'file')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_journal_file'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        $event = new JournalEvent($request, $journal, $this->getUser(), 'delete');
        $dispatcher->dispatch(JournalEvents::JOURNAL_SUBMISSION_FILES_CHANGE, $event);

        return $this->redirectToRoute('ojs_journal_file_index', ['journalId' => $journal->getId()]);
    }
}
