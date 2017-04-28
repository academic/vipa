<?php

namespace Vipa\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Vipa\AdminBundle\Form\Type\ContactType;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\JournalBundle\Entity\JournalContact;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Vipa\AdminBundle\Events\AdminEvent;
use Vipa\AdminBundle\Events\AdminEvents;

/**
 * JournalContact controller.
 */
class AdminContactController extends Controller
{
    /**
     * Lists all JournalContact entities.
     * 
     * @param  Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('VipaJournalBundle:JournalContact', 'admin');
        $grid = $this->get('grid');
        $grid->setSource($source);
        $gridAction = $this->get('grid_action');

        $rowAction = [];
        $rowAction[] = $gridAction->showAction('vipa_admin_contact_show', 'id');
        $rowAction[] = $gridAction->editAction('vipa_admin_contact_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('vipa_admin_contact_delete', 'id');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('VipaAdminBundle:AdminContact:index.html.twig', ['grid' => $grid]);
    }

    /**
     * Creates a new JournalContact entity.
     *
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new JournalContact();
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('successful.create');

            $event = new AdminEvent([
                'eventType' => 'create',
                'entity'    => $entity,
            ]);
            $dispatcher->dispatch(AdminEvents::ADMIN_CONTACT_CHANGE, $event);
            return $this->redirectToRoute(
                'vipa_admin_contact_show',
                ['id' => $entity->getId()]
            );
        }

        return $this->render(
            'VipaAdminBundle:AdminContact:new.html.twig',
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
     * @return Form The form
     */
    private function createCreateForm(JournalContact $entity)
    {
        $options = array(
            'action' => $this->generateUrl('vipa_admin_contact_create'),
            'method' => 'POST',
        );
        
        $form = $this->createForm(new ContactType(), $entity, $options);
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
        $entity = new JournalContact();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'VipaAdminBundle:AdminContact:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a JournalContact entity.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        
        /** @var JournalContact $entity */
        $entity = $em
            ->getRepository('VipaJournalBundle:JournalContact')
            ->findOneBy(['id' => $id]);

        $this->throw404IfNotFound($entity);
        
        $entity->setDefaultLocale($request->getDefaultLocale());
        
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_admin_contact'.$entity->getId());

        return $this->render(
            'VipaAdminBundle:AdminContact:show.html.twig',
            array(
                'entity' => $entity,
                'token'  => $token,
            )
        );
    }

    /**
     * Displays a form to edit an existing JournalContact entity.
     *
     * @param  integer $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var JournalContact $entity */
        $entity = $em
            ->getRepository('VipaJournalBundle:JournalContact')
            ->findOneBy(['id' => $id]);

        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_admin_contact'.$entity->getId());

        return $this->render(
            'VipaAdminBundle:AdminContact:edit.html.twig',
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
     * @return Form The form
     */
    private function createEditForm(JournalContact $entity)
    {
        $form = $this->createForm(
            new ContactType(),
            $entity,
            array(
                'method' => 'PUT',
                'action' => $this->generateUrl(
                    'vipa_admin_contact_update',
                    ['id' => $entity->getId()]
                ),
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }

    /**
     * Edits an existing JournalContact entity.
     *
     * @param  Request $request
     * @param  integer $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var JournalContact $entity */
        $entity = $em
            ->getRepository('VipaJournalBundle:JournalContact')
            ->findOneBy(['id' => $id]);

        $this->throw404IfNotFound($entity);
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            $event = new AdminEvent([
                'eventType' => 'update',
                'entity'    => $entity,
            ]);
            $dispatcher->dispatch(AdminEvents::ADMIN_CONTACT_CHANGE, $event);
            return $this->redirectToRoute(
                'vipa_admin_contact_edit',
                ['id' => $entity->getId()]
            );
        }

        return $this->render(
            'VipaAdminBundle:AdminContact:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a JournalContact entity.
     * @param  Request $request
     * @param  integer $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var JournalContact $entity */
        $entity = $em
            ->getRepository('VipaJournalBundle:JournalContact')
            ->findOneBy(['id' => $id]);

        $this->throw404IfNotFound($entity);
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('vipa_admin_contact'.$entity->getId());

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $this->get('vipa_core.delete.service')->check($entity);

        $event = new AdminEvent([
            'eventType' => 'delete',
            'entity'    => $entity,
        ]);
        $dispatcher->dispatch(AdminEvents::ADMIN_CONTACT_CHANGE, $event);

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('vipa_admin_contact_index');
    }
}
