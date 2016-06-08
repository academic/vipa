<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\AdminBundle\Form\Type\PublisherType;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Publisher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Ojs\AdminBundle\Events\AdminEvent;
use Ojs\AdminBundle\Events\AdminEvents;

/**
 * Publisher controller.
 *
 */
class AdminPublisherController extends Controller
{
    /**
     * Lists all Publisher entities.
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $cache = $this->get('array_cache');
        $router = $this->get('router');
        $source = new Entity('OjsJournalBundle:Publisher');
        $source->manipulateRow(
            function (Row $row) use ($request, $cache, $router) {
                /* @var Publisher $entity */
                $entity = $row->getEntity();
                $entity->setDefaultLocale($request->getDefaultLocale());
                if (!is_null($entity)) {
                    if($cache->contains('grid_row_id_'.$entity->getId())){
                        $row->setClass('hidden');
                    }else{
                        $cache->save('grid_row_id_'.$entity->getId(), true);
                        $publisherLinkTemplate = $entity->getNameTranslations();
                        if($entity->isIndexable()){
                            $generatePublisherLink = $router->generate('ojs_publisher_page', [
                                'slug' => $entity->getSlug(),
                            ]);
                            $publisherLinkTemplate = '<a target="_blank" href="'.$generatePublisherLink.'">'.$entity->getNameTranslations().'</a>';
                        }
                        $row->setField('translations.name', $publisherLinkTemplate);
                    }
                }

                return $row;
            }
        );
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('ojs_admin_publisher_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_publisher_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_publisher_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsAdminBundle:AdminPublisher:index.html.twig', $data);
    }

    /**
     * Creates a new Publisher entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $entity = new Publisher();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            $event = new AdminEvent([
                'eventType' => 'create',
                'entity' => $entity
            ]);
            $dispatcher->dispatch(AdminEvents::PUBLISHER_CHANGE, $event);
            return $this->redirectToRoute('ojs_admin_publisher_show', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsAdminBundle:AdminPublisher:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Publisher entity.
     *
     * @param Publisher $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Publisher $entity)
    {
        $form = $this->createForm(
            new PublisherType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_publisher_create'),
                'method' => 'POST'
            )
        );

        return $form;
    }

    /**
     * Displays a form to create a new Publisher entity.
     *
     */
    public function newAction()
    {
        $entity = new Publisher();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminPublisher:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Publisher entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Publisher')->find($id);
        $this->throw404IfNotFound($entity);

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_publisher' . $entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminPublisher:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing Publisher entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Publisher $entity */
        $entity = $em->getRepository('OjsJournalBundle:Publisher')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminPublisher:edit.html.twig',
            array(
                'entity' => $entity,
                'form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Publisher entity.
     *
     * @param Publisher $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Publisher $entity)
    {
        $form = $this->createForm(
            new PublisherType($entity->getId()),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_publisher_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        return $form;
    }

    /**
     * Edits an existing Publisher entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Publisher $entity */
        $entity = $em->getRepository('OjsJournalBundle:Publisher')->find($id);
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.update');

            $event = new AdminEvent([
                'eventType' => 'update',
                'entity' => $entity
            ]);
            $dispatcher->dispatch(AdminEvents::PUBLISHER_CHANGE, $event);
            return $this->redirectToRoute('ojs_admin_publisher_edit', ['id' => $id]);
        }

        return $this->render(
            'OjsAdminBundle:AdminPublisher:edit.html.twig',
            array(
                'entity' => $entity,
                'form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request                                            $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Publisher')->find($id);
        $this->throw404IfNotFound($entity);

        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_publisher' . $id);
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $this->get('ojs_core.delete.service')->check($entity);

        $event = new AdminEvent([
            'eventType' => 'delete',
            'entity' => $entity
        ]);
        $dispatcher->dispatch(AdminEvents::PUBLISHER_CHANGE, $event);
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirect($this->generateUrl('ojs_admin_publisher_index'));
    }
}
