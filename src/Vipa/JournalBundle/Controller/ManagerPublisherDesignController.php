<?php

namespace Vipa\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Vipa\AdminBundle\Form\Type\PublisherDesignType;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\JournalBundle\Entity\Publisher;
use Vipa\JournalBundle\Entity\PublisherDesign;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * PublisherDesign controller.
 *
 */
class ManagerPublisherDesignController extends Controller
{
    /**
     * Lists all PublisherDesigns entities.
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
        $source = new Entity('VipaJournalBundle:PublisherDesign');
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
            'vipa_publisher_manager_design_show',
            ['publisherId' => $publisher->getId(), 'id']
        );
        $rowAction[] = $gridAction->editAction(
            'vipa_publisher_manager_design_edit',
            ['publisherId' => $publisher->getId(), 'id']
        );
        $rowAction[] = $gridAction->deleteAction(
            'vipa_publisher_manager_design_delete',
            ['publisherId' => $publisher->getId(), 'id']
        );

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;
        $data['publisher'] = $publisher;

        return $grid->getGridResponse('VipaJournalBundle:ManagerPublisherDesign:index.html.twig', $data);
    }

    /**
     * Creates a new PublisherDesign entity.
     *
     * @param Request $request
     * @param integer $publisherId
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request, $publisherId)
    {
        $em = $this->getDoctrine()->getManager();
        $publisher = $em->getRepository('VipaJournalBundle:Publisher')->find($publisherId);
        $this->throw404IfNotFound($publisher);
        if (!$this->isGrantedForPublisher($publisher)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new PublisherDesign();
        $form = $this->createCreateForm($entity, $publisher);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setPublisher($publisher);
            $entity->setContent(
                $this->prepareDesignContent($entity->getEditableContent())
            );
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute(
                'vipa_publisher_manager_design_show',
                ['publisherId' => $publisher->getId(), 'id' => $entity->getId()]
            );
        }

        return $this->render(
            'VipaJournalBundle:ManagerPublisherDesign:new.html.twig',
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
     * @param PublisherDesign $entity
     * @param Publisher $publisher
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(PublisherDesign $entity, Publisher $publisher)
    {
        $form = $this->createForm(
            new PublisherDesignType(),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'vipa_publisher_manager_design_create',
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
     * @param  String $editableContent
     * @return String
     */
    private function prepareDesignContent($editableContent)
    {
        $editableContent = preg_replace_callback(
            '/<span\s*class\s*=\s*"\s*design-hide-block[^"]*"[^>]*>.*<\s*\/\s*span\s*>.*<span\s*class\s*=\s*"\s*design-hide-endblock[^"]*"[^>]*>.*<\s*\/\s*span\s*>/Us',
            function ($matches) {
                preg_match('/<!---.*--->/Us', $matches[0], $matched);

                return str_ireplace(['<!---', '--->'], '', $matched[0]);
            },
            $editableContent
        );
        $editableContent = preg_replace_callback(
            '/<span\s*class\s*=\s*"\s*design-hide-span[^"]*"[^>]*>.*<\s*\/\s*span\s*>/Us',
            function ($matches) {
                preg_match('/<!---.*--->/Us', $matches[0], $matched);

                return str_ireplace(['<!---', '--->'], '', $matched[0]);
            },
            $editableContent
        );
        $editableContent = preg_replace_callback(
            '/<span\s*class\s*=\s*"\s*design-inline[^"]*"[^>]*>.*<\s*\/\s*span\s*>/Us',
            function ($matches) {
                preg_match('/title\s*=\s*"\s*{.*}\s*"/Us', $matches[0], $matched);
                $matched[0] = preg_replace('/title\s*=\s*"/Us', '', $matched[0]);

                return str_replace('"', '', $matched[0]);
            },
            $editableContent
        );
        $editableContent = str_ireplace('<!--gm-editable-region-->', '', $editableContent);
        $editableContent = str_ireplace('<!--/gm-editable-region-->', '', $editableContent);

        return $editableContent;
    }

    /**
     * Displays a form to create a new PublisherDesign entity.
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
        $entity = new PublisherDesign();
        $form = $this->createCreateForm($entity, $publisher);

        return $this->render(
            'VipaJournalBundle:ManagerPublisherDesign:new.html.twig',
            array(
                'entity' => $entity,
                'publisher' => $publisher,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a PublisherDesign entity.
     *
     * @param integer $publisherId
     * @param PublisherDesign $entity
     * @return Response
     */
    public function showAction($publisherId, PublisherDesign $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $publisher = $em->getRepository('VipaJournalBundle:Publisher')->find($publisherId);
        $this->throw404IfNotFound($publisher);
        if (!$this->isGrantedForPublisher($publisher)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_publisher_manager_design'.$entity->getId());

        return $this->render(
            'VipaJournalBundle:ManagerPublisherDesign:show.html.twig',
            [
                'entity' => $entity,
                'publisher' => $publisher,
                'token' => $token
            ]
        );
    }

    /**
     * Displays a form to edit an existing PublisherDesign entity.
     *
     * @param integer $publisherId
     * @param PublisherDesign $entity
     * @return Response
     */
    public function editAction($publisherId, PublisherDesign $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $publisher = $em->getRepository('VipaJournalBundle:Publisher')->find($publisherId);
        $this->throw404IfNotFound($publisher);
        if (!$this->isGrantedForPublisher($publisher)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity->setEditableContent($this->prepareEditContent($entity->getEditableContent()));
        $editForm = $this->createEditForm($entity, $publisher);

        return $this->render(
            'VipaJournalBundle:ManagerPublisherDesign:edit.html.twig',
            array(
                'entity' => $entity,
                'publisher' => $publisher,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  String $editableContent
     * @return String
     */
    private function prepareEditContent($editableContent)
    {
        $editableContent = str_ireplace('<!--raw-->', '{% raw %}<!--raw-->', $editableContent);
        $editableContent = str_ireplace('<!--endraw-->', '{% endraw %}<!--endraw-->', $editableContent);

        $editableContent = preg_replace_callback(
            '/<span\s*class\s*=\s*"\s*design-inline[^"]*"[^>]*>.*<\s*\/\s*span\s*>/Us',
            function ($matches) {
                return preg_replace_callback(
                    '/{{.*}}/Us',
                    function ($matched) {
                        return '{{ "'.addcslashes($matched[0], '"').'" }}';
                    },
                    $matches[0]
                );
            },
            $editableContent
        );

        return $editableContent;
    }

    /**
     * Creates a form to edit a PublisherTypes entity.
     *
     * @param PublisherDesign $entity The entity
     * @param Publisher $publisher
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(PublisherDesign $entity, Publisher $publisher)
    {
        $form = $this->createForm(
            new PublisherDesignType(),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'vipa_publisher_manager_design_update',
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
     * Edits an existing PublisherDesigns entity.
     *
     * @param Request $request
     * @param integer $publisherId
     * @param PublisherDesign $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $publisherId, PublisherDesign $entity)
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
            $entity->setContent(
                $this->prepareDesignContent($entity->getEditableContent())
            );
            $entity->setPublisher($publisher);

            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute(
                'vipa_publisher_manager_design_edit',
                [
                    'id' => $entity->getId(),
                    'publisherId' => $publisher->getId()
                ]
            );
        }

        return $this->render(
            'VipaJournalBundle:ManagerPublisherDesign:edit.html.twig',
            array(
                'entity' => $entity,
                'publisher' => $publisher,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request $request
     * @param  PublisherDesign $entity
     * @param  integer $publisherId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws TokenNotFoundException
     */
    public function deleteAction(Request $request, PublisherDesign $entity, $publisherId)
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
        $token = $csrf->getToken('vipa_publisher_manager_design'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('vipa_publisher_manager_design_index', ['publisherId' => $publisherId]);
    }
}
