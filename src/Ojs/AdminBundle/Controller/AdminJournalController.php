<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Ojs\AdminBundle\Form\Type\JournalType;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * Journal controller.
 */
class AdminJournalController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction(Request $request)
    {
        if (!$this->isGranted('VIEW', new Journal())) {
            throw new AccessDeniedException("You not authorized for list journals!");
        }

        $source = new Entity('OjsJournalBundle:Journal');
        $source->manipulateRow(
            function (Row $row) use ($request)
            {
                /* @var Journal $entity */
                $entity = $row->getEntity();
                $entity->setDefaultLocale($request->getDefaultLocale());
                if(!is_null($entity)){
                    $row->setField('title', $entity->getTitle());
                }
                return $row;
            }
        );
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('ojs_admin_journal_show', 'id');
        $rowAction[] = $gridAction
            ->editAction('ojs_journal_settings_index', 'id')
            ->setRouteParametersMapping(['id' => 'journalId']);
        $rowAction[] = $gridAction->cmsAction();
        $rowAction[] = $gridAction->deleteAction('ojs_admin_journal_delete', 'id');
        $rowAction[] = (new RowAction('Manage', 'ojs_journal_dashboard_index'))
            ->setRouteParameters('id')
            ->setRouteParametersMapping(array('id' => 'journalId'))
            ->setAttributes(
                array(
                    'class' => 'btn btn-success btn-xs',
                    'data-toggle' => 'tooltip',
                    'title' => "Manage"
                )
            );

        $actionColumn->setRowActions($rowAction);

        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsAdminBundle:AdminJournal:index.html.twig', $data);
    }

    /**
     * Returns setupFinished == false journals
     *
     * @param Request $request
     * @return Response
     */
    public function notFinishedAction(Request $request)
    {
        if (!$this->isGranted('VIEW', new Journal())) {
            throw new AccessDeniedException("You not authorized for list journals!");
        }
        $source = new Entity('OjsJournalBundle:Journal');
        $tableAlias = $source->getTableAlias();

        $source->manipulateQuery(
            function (QueryBuilder $query) use ($tableAlias)
            {
                $query->andWhere($tableAlias . '.setupFinished = 0');
            }
        );
        $source->manipulateRow(
            function (Row $row) use ($request)
            {
                /* @var Journal $entity */
                $entity = $row->getEntity();
                $entity->setDefaultLocale($request->getDefaultLocale());
                if(!is_null($entity)){
                    $row->setField('title', $entity->getTitle());
                    $row->setField('subtitle', $entity->getSubtitle());
                    $row->setField('description', $entity->getDescription());
                }
                return $row;
            }
        );
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('ojs_admin_journal_show', 'id');
        $rowAction[] = $gridAction
            ->editAction('ojs_journal_settings_index', 'id')
            ->setRouteParametersMapping(['id' => 'journalId']);
        $rowAction[] = $gridAction->cmsAction();
        $rowAction[] = $gridAction->deleteAction('ojs_admin_journal_delete', 'id');
        $rowAction[] = (new RowAction('Manage', 'ojs_journal_dashboard_index'))
            ->setRouteParameters('id')
            ->setRouteParametersMapping(array('id' => 'journalId'))
            ->setAttributes(
                array('class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip', 'title' => "Manage")
            );

        $actionColumn->setRowActions($rowAction);

        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsAdminBundle:AdminJournal:index.html.twig', $data);
    }

    /**
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $entity = new Journal();
        $entity->setCurrentLocale($request->getDefaultLocale());
        if (!$this->isGranted('CREATE', $entity)) {
            throw new AccessDeniedException("You not authorized for create a journal!");
        }
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirect($this->generateUrl('ojs_admin_journal_show', array('id' => $entity->getId())));
        }

        return $this->render(
            'OjsAdminBundle:AdminJournal:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Journal entity.
     * @param  Journal $entity The entity
     * @return Form    The form
     */
    private function createCreateForm(Journal $entity)
    {
        $form = $this->createForm(
            new JournalType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_journal_create'),
                'method' => 'POST',
            )
        );

        return $form;
    }

    /**
     * Displays a form to create a new Journal entity.
     *
     * @return Response
     */
    public function newAction(Request $request)
    {
        $entity = new Journal();
        $entity->setCurrentLocale($request->getDefaultLocale());
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminJournal:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Journal entity.
     *
     * @param Request $request
     * @param Journal $entity
     * @return Response
     */
    public function showAction(Request $request, Journal $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('VIEW', $entity))
            throw new AccessDeniedException("You not authorized for view this journal!");

        $entity->setDefaultLocale($request->getDefaultLocale());
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_journal'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminJournal:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * @param  Request          $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Journal')->find($id);

        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You not authorized for delete this journal!");
        }
        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_journal'.$id);
        if ($token->getValue() !== $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_admin_journal_index');
    }
}
