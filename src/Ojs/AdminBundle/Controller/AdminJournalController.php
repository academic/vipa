<?php

namespace Ojs\AdminBundle\Controller;

use Doctrine\ORM\Query;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\AdminBundle\Form\Type\JournalType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Ojs\JournalBundle\Entity\File;

/**
 * Journal controller.
 */
class AdminJournalController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        if (!$this->isGranted('VIEW', new Journal())) {
            throw new AccessDeniedException("You not authorized for list journals!");
        }
        $source = new Entity('OjsJournalBundle:Journal');
        $source->addHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('ojs_admin_journal_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_journal_edit', 'id');
        $rowAction[] = $gridAction->cmsAction();
        $rowAction[] = $gridAction->deleteAction('ojs_admin_journal_delete', 'id');
        $rowAction[] = (new RowAction('Manage', 'change_selected_journal'))
            ->setRouteParameters('id')
            ->setRouteParametersMapping(array('id' => 'journal_id'))
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
     * Returns setupStatus == false journals
     * @return Response
     */
    public function notFinishedAction()
    {
        if (!$this->isGranted('VIEW', new Journal())) {
            throw new AccessDeniedException("You not authorized for list journals!");
        }
        $source = new Entity('OjsJournalBundle:Journal');
        $tableAlias = $source->getTableAlias();

        $source->manipulateQuery(
            function ($query) use ($tableAlias)
            {
                $query->andWhere($tableAlias . '.setup_status = 0');
            }
        );
        $source->addHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('ojs_admin_journal_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_journal_edit', 'id');
        $rowAction[] = $gridAction->cmsAction();
        $rowAction[] = $gridAction->deleteAction('ojs_admin_journal_delete', 'id');
        $rowAction[] = (new RowAction('Manage', 'change_selected_journal'))
            ->setRouteParameters('id')
            ->setRouteParametersMapping(array('id' => 'journal_id'))
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
        if (!$this->isGranted('CREATE', $entity)) {
            throw new AccessDeniedException("You not authorized for create a journal!");
        }
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $header = $request->request->get('header');
            $cover = $request->request->get('cover');
            $logo = $request->request->get('logo');
            if ($header) {
                $entity->setHeaderOptions(json_encode($header));
            }
            if ($cover) {
                $entity->setImageOptions(json_encode($cover));
            }
            if ($logo) {
                $entity->setLogoOptions(json_encode($logo));
            }
            $entity->setTranslatableLocale($request->getDefaultLocale());
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
    public function newAction()
    {
        $entity = new Journal();
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
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Journal')->find($id);
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('VIEW', $entity))
            throw new AccessDeniedException("You not authorized for view this journal!");

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_journal'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminJournal:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing Journal entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Journal $entity */
        $entity = $em->getRepository('OjsJournalBundle:Journal')->find($id);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You not authorized for edit this journal!");
        }

        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminJournal:edit.html.twig',
            array(
                'entity' => $entity,
                'form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Journal entity.
     * @param  Journal $entity The entity
     * @return Form    The form
     */
    private function createEditForm(Journal $entity)
    {
        $form = $this->createForm(
            new JournalType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_journal_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        return $form;
    }

    /**
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Journal $entity */
        $entity = $em->getRepository('OjsJournalBundle:Journal')->find($id);

        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You not authorized for edit this journal!");
        }
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->submit($request);
        if ($editForm->isValid()) {
            if($request->get('competing_interest_file_id') == ''){
                if($request->get('competing_interest_file') !== ''){
                    $competingInterestFile = new File();
                    $competingInterestFile->setName('Competing Interest File');
                    $competingInterestFile->setSize($request->get('competing_interest_file_size'));
                    $competingInterestFile->setMimeType($request->get('competing_interest_file_mime_type'));
                    $competingInterestFile->setPath($request->get('competing_interest_file'));
                    $em->persist($competingInterestFile);
                    $em->flush();
                    $entity->setCompetingInterestFile($competingInterestFile);
                }
            }
            $header = $request->request->get('header');
            $cover = $request->request->get('cover');
            $logo = $request->request->get('logo');
            if ($header) {
                $entity->setHeaderOptions(json_encode($header));
            }
            if ($cover) {
                $entity->setImageOptions(json_encode($cover));
            }
            if ($logo) {
                $entity->setLogoOptions(json_encode($logo));
            }
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('ojs_admin_journal_edit',  ['id' => $id]);
        }

        return $this->render(
            'OjsAdminBundle:AdminJournal:edit.html.twig',
            array(
                'entity' => $entity,
                'form' => $editForm->createView(),
            )
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
