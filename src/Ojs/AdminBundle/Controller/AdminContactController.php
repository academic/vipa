<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Ojs\AdminBundle\Form\Type\ContactType;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalContact;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

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
        if (!$this->isGranted('VIEW', new JournalContact())) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }

        $source = new Entity('OjsJournalBundle:JournalContact');
        $source->manipulateRow(
            function (Row $row) use ($request)
            {
                /* @var JournalContact $entity */
                $entity = $row->getEntity();
                $entity->setDefaultLocale($request->getDefaultLocale());

                if (!is_null($entity)){
                    $row->setField('title', $entity->getTitle());
                    $row->setField('contactTypeName', $entity->getContactType()->getName());
                }

                return $row;
            }
        );

        $grid = $this->get('grid');
        $grid->setSource($source);
        $gridAction = $this->get('grid_action');

        $rowAction = [];
        $rowAction[] = $gridAction->showAction('ojs_admin_contact_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_contact_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_contact_delete', 'id');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('OjsAdminBundle:AdminContact:index.html.twig', ['grid' => $grid]);
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

        if (!$this->isGranted('CREATE', $entity)) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('Successfully created');

            return $this->redirectToRoute(
                'ojs_admin_contact_show',
                ['id' => $entity->getId()]
            );
        }

        return $this->render(
            'OjsAdminBundle:AdminContact:new.html.twig',
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
            'action' => $this->generateUrl('ojs_admin_contact_create'),
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
        
        if (!$this->isGranted('CREATE', $entity)) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminContact:new.html.twig',
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
            ->getRepository('OjsJournalBundle:JournalContact')
            ->findOneBy(['id' => $id]);

        $this->throw404IfNotFound($entity);
        
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        
        $entity->setDefaultLocale($request->getDefaultLocale());
        
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_contact'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminContact:show.html.twig',
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
            ->getRepository('OjsJournalBundle:JournalContact')
            ->findOneBy(['id' => $id]);

        $this->throw404IfNotFound($entity);
        
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        
        $editForm = $this->createEditForm($entity);
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_contact'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminContact:edit.html.twig',
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
                    'ojs_admin_contact_update',
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
            ->getRepository('OjsJournalBundle:JournalContact')
            ->findOneBy(['id' => $id]);

        $this->throw404IfNotFound($entity);
        
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('Successfully updated');

            return $this->redirectToRoute(
                'ojs_admin_contact_edit',
                ['id' => $entity->getId()]
            );
        }

        return $this->render(
            'OjsAdminBundle:AdminContact:edit.html.twig',
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
            ->getRepository('OjsJournalBundle:JournalContact')
            ->findOneBy(['id' => $id]);

        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_contact'.$entity->getId());

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('Successfully removed');

        return $this->redirectToRoute('ojs_admin_contact_index');
    }
}
