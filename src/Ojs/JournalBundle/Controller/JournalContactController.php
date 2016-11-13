<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalContact;
use Ojs\JournalBundle\Event\JournalContact\JournalContactEvents;
use Ojs\JournalBundle\Event\JournalEvent;
use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\ListEvent;
use Ojs\JournalBundle\Form\Type\JournalContactType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * JournalContact controller.
 *
 */
class JournalContactController extends Controller
{
    /**
     * Lists all JournalContact entities.
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');
        if (!$this->isGranted('VIEW', $journal, 'contacts')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $source = new Entity('OjsJournalBundle:JournalContact');
        $grid = $this->get('grid');
        $grid->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction = [];

        $rowAction[] = $gridAction->showAction(
            'ojs_journal_journal_contact_show',
            ['id', 'journalId' => $journal->getId()]
        );
        $rowAction[] = $gridAction->editAction(
            'ojs_journal_journal_contact_edit',
            ['id', 'journalId' => $journal->getId()]
        );
        $rowAction[] = $gridAction->deleteAction(
            'ojs_journal_journal_contact_delete',
            ['id', 'journalId' => $journal->getId()]
        );
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $listEvent = new ListEvent();
        $listEvent->setGrid($grid);
        $eventDispatcher->dispatch(JournalContactEvents::LISTED, $listEvent);
        $grid = $listEvent->getGrid();

        return $grid->getGridResponse('OjsJournalBundle:JournalContact:index.html.twig');
    }

    /**
     * Creates a new JournalContact entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');
        $em = $this->getDoctrine()->getManager();

        if (!$this->isGranted('CREATE', $journal, 'contacts')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $entity = new JournalContact();
        $form = $this->createCreateForm($entity, $journal->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setJournal($journal);

            $event = new JournalItemEvent($entity);
            $eventDispatcher->dispatch(JournalContactEvents::PRE_CREATE, $event);

            $em->persist($event->getItem());
            $em->flush();

            $event = new JournalItemEvent($event->getItem());
            $eventDispatcher->dispatch(JournalContactEvents::POST_CREATE, $event);

            if ($event->getResponse()) {
                return $event->getResponse();
            }

            $this->successFlashBag('successful.create');

            return $this->redirectToRoute(
                'ojs_journal_journal_contact_show',
                array('id' => $entity->getId(), 'journalId' => $journal->getId())
            );
        }

        return $this->render(
            'OjsJournalBundle:JournalContact:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a JournalContact entity.
     *
     * @param  JournalContact $entity The entity
     * @param  Integer $journalId
     * @return Form           The form
     */
    private function createCreateForm(JournalContact $entity, $journalId)
    {
        $options = array(
            'action' => $this->generateUrl('ojs_journal_journal_contact_create', array('journalId' => $journalId)),
            'method' => 'POST',
        );
        $form = $this->createForm(new JournalContactType(), $entity, $options);

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new JournalContact entity.
     *
     * @return Response
     */
    public function newAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'contacts')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $entity = new JournalContact();
        $form = $this->createCreateForm($entity, $journal->getId());

        return $this->render(
            'OjsJournalBundle:JournalContact:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a JournalContact entity.
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'contacts')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        /** @var JournalContact $entity */
        $entity = $em->getRepository('OjsJournalBundle:JournalContact')->find($id);
        $this->throw404IfNotFound($entity);

        $entity->setDefaultLocale($request->getDefaultLocale());
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_journal_contact'.$entity->getId());

        return $this->render(
            'OjsJournalBundle:JournalContact:show.html.twig',
            array(
                'entity' => $entity,
                'token' => $token,
            )
        );
    }

    /**
     * Displays a form to edit an existing JournalContact entity.
     *
     * @param  integer  $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'contacts')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        /** @var JournalContact $entity */
        $entity = $em->getRepository('OjsJournalBundle:JournalContact')->find($id);
        $this->throw404IfNotFound($entity);

        $editForm = $this->createEditForm($entity, $journal->getId());

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_journal_contact'.$entity->getId());

        return $this->render(
            'OjsJournalBundle:JournalContact:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'token' => $token,
            )
        );
    }

    /**
     * Creates a form to edit a JournalContact entity.
     *
     * @param JournalContact $entity The entity
     * @param Integer $journalId
     *
     * @return Form The form
     */
    private function createEditForm(JournalContact $entity, $journalId)
    {
        $form = $this->createForm(
            new JournalContactType(),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'ojs_journal_journal_contact_update',
                    array('id' => $entity->getId(), 'journalId' => $journalId)
                ),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing JournalContact entity.
     *
     * @param  Request                   $request
     * @param  integer                   $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');
        $em = $this->getDoctrine()->getManager();

        if (!$this->isGranted('EDIT', $journal, 'contacts')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        /** @var JournalContact $entity */
        $entity = $em->getRepository('OjsJournalBundle:JournalContact')->find($id);
        $this->throw404IfNotFound($entity);

        $editForm = $this->createEditForm($entity, $journal->getId());
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $event = new JournalItemEvent($entity);
            $eventDispatcher->dispatch(JournalContactEvents::PRE_UPDATE, $event);
            $em->persist($event->getItem());
            $em->flush();

            $event = new JournalItemEvent($event->getItem());
            $eventDispatcher->dispatch(JournalContactEvents::POST_UPDATE, $event);

            if ($event->getResponse()) {
                return $event->getResponse();
            }

            $this->successFlashBag('successfully.update');
            return $this->redirectToRoute(
                'ojs_journal_journal_contact_edit',
                array('id' => $entity->getId(), 'journalId' => $journal->getId())
            );
        }

        return $this->render(
            'OjsJournalBundle:JournalContact:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a JournalContact entity.
     * @param  Request          $request
     * @param  integer          $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');
        $em = $this->getDoctrine()->getManager();
        if (!$this->isGranted('DELETE', $journal, 'contacts')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }

        /** @var JournalContact $entity */
        $entity = $em->getRepository('OjsJournalBundle:JournalContact')->find($id);
        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');

        $token = $csrf->getToken('ojs_journal_journal_contact'.$entity->getId());

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $event = new JournalItemEvent($entity);
        $eventDispatcher->dispatch(JournalContactEvents::PRE_DELETE, $event);

        $em->remove($entity);
        $em->flush();

        $event = new JournalEvent($journal);
        $eventDispatcher->dispatch(JournalContactEvents::POST_DELETE, $event);

        if ($event->getResponse()) {
            return $event->getResponse();
        }

        $this->successFlashBag('successfully.remove');
        return $this->redirectToRoute('ojs_journal_journal_contact_index', array('journalId' => $journal->getId()));
    }
}
