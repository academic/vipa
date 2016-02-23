<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Ojs\AdminBundle\Form\Type\SubjectType;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\CoreBundle\Helper\TreeHelper;
use Ojs\JournalBundle\Entity\Subject;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Ojs\AdminBundle\Events\AdminEvent;
use Ojs\AdminBundle\Events\AdminEvents;

/**
 * Subject controller.
 *
 */
class AdminSubjectController extends Controller
{

    /**
     * Lists all Subject entities.
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        if (!$this->isGranted('VIEW', new Subject())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $cache = $this->get('array_cache');
        $source = new Entity("OjsJournalBundle:Subject");
        $source->manipulateRow(
            function (Row $row) use ($request, $cache) {
                /* @var Subject $entity */
                $entity = $row->getEntity();
                $entity->setDefaultLocale($request->getDefaultLocale());
                if (!is_null($entity)) {
                    if($cache->contains('grid_row_id_'.$entity->getId())){
                        $row->setClass('hidden');
                    }else{
                        $cache->save('grid_row_id_'.$entity->getId(), true);
                        $row->setField('translations.subject', $entity->getSubjectTranslations());
                        $row->setField('translations.description', $entity->getDescriptionTranslations());
                    }
                }

                return $row;
            }
        );

        $grid = $this->get('grid')->setSource($source);

        $gridAction = $this->get('grid_action');
        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('ojs_admin_subject_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_subject_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_subject_delete', 'id');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        /** @var ArrayCollection|Subject[] $all */
        $all = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:Subject')
            ->findAll();

        $data = [
            'grid' => $grid,
            'tree' => TreeHelper::createSubjectTreeView(TreeHelper::SUBJECT_ADMIN, $this->get('router'), $all)
        ];

        return $grid->getGridResponse('OjsAdminBundle:AdminSubject:index.html.twig', $data);
    }

    /**
     * Creates a new Subject entity.
     *
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new Subject())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $entity = new Subject();
        $entity->setCurrentLocale($request->getDefaultLocale());
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setSlug($entity->getTranslationByLocale($request->getDefaultLocale())->getSubject());
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            $event = new AdminEvent($request, null, null, $this->getUser(), 'create');
            $dispatcher->dispatch(AdminEvents::ADMIN_SUBJECT_CHANGE, $event);
            return $this->redirectToRoute('ojs_admin_subject_show', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsAdminBundle:AdminSubject:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Subject entity.
     *
     * @param Subject $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Subject $entity)
    {
        $form = $this->createForm(
            new SubjectType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_subject_create'),
                'method' => 'POST',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Subject entity.
     *
     */
    public function newAction()
    {
        if (!$this->isGranted('CREATE', new Subject())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new Subject();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminSubject:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Subject entity.
     *
     * @param Request $request
     * @param Subject $entity
     * @return Response
     */
    public function showAction(Request $request, Subject $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $entity->setDefaultLocale($request->getDefaultLocale());
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_subject'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminSubject:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing Subject entity.
     *
     * @param  Subject $entity
     * @return Response
     */
    public function editAction(Subject $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminSubject:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Subject entity.
     *
     * @param Subject $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Subject $entity)
    {
        $form = $this->createForm(
            new SubjectType($entity->getId()),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_subject_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Subject entity.
     *
     * @param  Request $request
     * @param  Subject $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, Subject $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $entity->setSlug($entity->getTranslationByLocale($request->getDefaultLocale())->getSubject());
            $em->flush();
            $this->successFlashBag('successful.update');

            $event = new AdminEvent($request, null, null, $this->getUser(), 'update', $entity);
            $dispatcher->dispatch(AdminEvents::ADMIN_SUBJECT_CHANGE, $event);
            return $this->redirectToRoute('ojs_admin_subject_edit', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsAdminBundle:AdminSubject:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request $request
     * @param  Subject $entity
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Subject $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $em = $this->getDoctrine()->getManager();

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_subject'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $this->get('ojs_core.delete.service')->check($entity);
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        $event = new AdminEvent($request, null, null, $this->getUser(), 'delete');
        $dispatcher->dispatch(AdminEvents::ADMIN_SUBJECT_CHANGE, $event);
        return $this->redirectToRoute('ojs_admin_subject_index');
    }
}
