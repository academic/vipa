<?php

namespace Vipa\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Vipa\AdminBundle\Form\Type\PublisherThemeType;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\JournalBundle\Entity\Publisher;
use Vipa\JournalBundle\Entity\PublisherTheme;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;


/**
 * PublisherThemes controller.
 *
 */
class ManagerPublisherThemeController extends Controller
{
    /**
     * Lists all PublisherThemes entities.
     *
     * @param integer $publisherId
     * @return Response
     */
    public function indexAction($publisherId)
    {
        $em = $this->getDoctrine()->getManager();
        $publisher = $em->getRepository('VipaJournalBundle:Publisher')->find($publisherId);
        $this->throw404IfNotFound($publisher);
        if (!$this->isGrantedForPublisher($publisher)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $source = new Entity('VipaJournalBundle:PublisherTheme');
        $alias = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $qb) use ($publisher, $alias) {
                $qb->andWhere($alias.'.publisher = :publisher')
                    ->setParameter('publisher', $publisher);
            }
        );
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction(
            'vipa_publisher_manager_theme_show',
            ['publisherId' => $publisher->getId(), 'id']
        );
        $rowAction[] = $gridAction->editAction(
            'vipa_publisher_manager_theme_edit',
            ['publisherId' => $publisher->getId(), 'id']
        );
        $rowAction[] = $gridAction->deleteAction(
            'vipa_publisher_manager_theme_delete',
            ['publisherId' => $publisher->getId(), 'id']
        );

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;
        $data['publisher'] = $publisher;

        return $grid->getGridResponse('VipaJournalBundle:ManagerPublisherTheme:index.html.twig', $data);
    }

    /**
     * Creates a new PublisherTheme entity.
     *
     * @param  integer $publisherId
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function createAction($publisherId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $publisher = $em->getRepository('VipaJournalBundle:Publisher')->find($publisherId);
        $this->throw404IfNotFound($publisher);
        if (!$this->isGrantedForPublisher($publisher)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new PublisherTheme();
        $form = $this->createCreateForm($entity, $publisher);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setPublisher($publisher);
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute(
                'vipa_publisher_manager_theme_show',
                [
                    'publisherId' => $publisher->getId(),
                    'id' => $entity->getId()
                ]
            );
        }

        return $this->render(
            'VipaJournalBundle:ManagerPublisherTheme:new.html.twig',
            array(
                'entity' => $entity,
                'publisher' => $publisher,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a PublisherTypes entity.
     *
     * @param PublisherTheme $entity The entity
     * @param Publisher $publisher
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(PublisherTheme $entity, Publisher $publisher)
    {
        $form = $this->createForm(
            new PublisherThemeType(),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'vipa_publisher_manager_theme_create',
                    ['publisherId' => $publisher->getId()]
                ),
                'method' => 'POST',
            )
        );
        $form->remove('publisher');
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new PublisherTheme entity.
     *
     * @param integer $publisherId
     * @return Response
     */
    public function newAction($publisherId)
    {
        $em = $this->getDoctrine()->getManager();
        $publisher = $em->getRepository('VipaJournalBundle:Publisher')->find($publisherId);
        $this->throw404IfNotFound($publisher);
        if (!$this->isGrantedForPublisher($publisher)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new PublisherTheme();
        $form = $this->createCreateForm($entity, $publisher);

        return $this->render(
            'VipaJournalBundle:ManagerPublisherTheme:new.html.twig',
            array(
                'entity' => $entity,
                'publisher' => $publisher,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a PublisherTheme entity.
     *
     * @param  integer $publisherId
     * @param  PublisherTheme $entity
     * @return Response
     */
    public function showAction($publisherId, PublisherTheme $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $publisher = $em->getRepository('VipaJournalBundle:Publisher')->find($publisherId);
        $this->throw404IfNotFound($publisher);
        if (!$this->isGrantedForPublisher($publisher)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_admin_publisher_theme'.$entity->getId());

        return $this->render(
            'VipaJournalBundle:ManagerPublisherTheme:show.html.twig',
            [
                'entity' => $entity,
                'publisher' => $publisher,
                'token' => $token
            ]
        );
    }

    /**
     * Displays a form to edit an existing PublisherTheme entity.
     *
     * @param integer $publisherId
     * @param PublisherTheme $entity
     * @return Response
     */
    public function editAction($publisherId, PublisherTheme $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $publisher = $em->getRepository('VipaJournalBundle:Publisher')->find($publisherId);
        $this->throw404IfNotFound($publisher);
        if (!$this->isGrantedForPublisher($publisher)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity, $publisher);

        return $this->render(
            'VipaJournalBundle:ManagerPublisherTheme:edit.html.twig',
            array(
                'entity' => $entity,
                'publisher' => $publisher,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a PublisherTypes entity.
     *
     * @param PublisherTheme $entity
     * @param Publisher $publisher
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm(PublisherTheme $entity, Publisher $publisher)
    {
        $form = $this->createForm(
            new PublisherThemeType(),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'vipa_publisher_manager_theme_update',
                    array('publisherId' => $publisher->getId(), 'id' => $entity->getId())
                ),
                'method' => 'PUT',
            )
        );
        $form->remove('publisher');
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing PublisherThemes entity.
     *
     * @param Request $request
     * @param integer $publisherId
     * @param PublisherTheme $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $publisherId, PublisherTheme $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $publisher = $em->getRepository('VipaJournalBundle:Publisher')->find($publisherId);
        $this->throw404IfNotFound($publisher);
        if (!$this->isGrantedForPublisher($publisher)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity, $publisher);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $entity->setPublisher($publisher);
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute(
                'vipa_publisher_manager_theme_edit',
                ['publisherId' => $publisherId, 'id' => $entity->getId()]
            );
        }

        return $this->render(
            'VipaJournalBundle:ManagerPublisherTheme:edit.html.twig',
            array(
                'entity' => $entity,
                'publisher' => $publisher,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param Request $request
     * @param PublisherTheme $entity
     * @param integer $publisherId
     * @return RedirectResponse
     * @throws TokenNotFoundException
     */
    public function deleteAction(Request $request, PublisherTheme $entity, $publisherId)
    {
        $em = $this->getDoctrine()->getManager();
        $publisher = $em->getRepository('VipaJournalBundle:Publisher')->find($publisherId);
        $this->throw404IfNotFound($publisher);
        if (!$this->isGrantedForPublisher($publisher)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('vipa_publisher_manager_theme'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $this->get('vipa_core.delete.service')->check($entity);
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('vipa_publisher_manager_theme_index', ['publisherId' => $publisherId]);
    }
}
