<?php

namespace Vipa\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\JournalBundle\Entity\JournalTheme;
use Vipa\JournalBundle\Form\Type\JournalThemeType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * Theme controller.
 *
 */
class JournalThemeController extends Controller
{

    /**
     * Lists all Theme entities.
     *
     */
    public function indexAction()
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'theme')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $source = new Entity('VipaJournalBundle:JournalTheme');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('vipa_journal_theme_show', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->editAction('vipa_journal_theme_edit', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->deleteAction('vipa_journal_theme_delete', ['id', 'journalId' => $journal->getId()]);

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('VipaJournalBundle:Theme:index.html.twig', $data);
    }

    /**
     * Creates a new Theme entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'theme')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }

        $entity = new JournalTheme();
        $entity->setJournal($journal);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');
            return $this->redirectToRoute('vipa_journal_theme_show', ['id' => $entity->getId(), 'journalId' => $journal->getId()]);
        }

        return $this->render(
            'VipaJournalBundle:Theme:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Theme entity.
     *
     * @param JournalTheme $entity The entity
     *
     * @return Form The form
     */
    private function createCreateForm(JournalTheme $entity)
    {
        $form = $this->createForm(
            new JournalThemeType(),
            $entity,
            array(
                'action' => $this->generateUrl('vipa_journal_theme_create', ['journalId' => $entity->getJournal()->getId()]),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Theme entity.
     *
     */
    public function newAction()
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'theme')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $entity = new JournalTheme();
        $entity->setJournal($journal);
        $form = $this->createCreateForm($entity);

        return $this->render(
            'VipaJournalBundle:Theme:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Theme entity.
     *
     * @param  integer  $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $journal = $this->get('vipa.journal_service')->getSelectedJournal();

        if (!$this->isGranted('VIEW', $journal, 'theme')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        /** @var JournalTheme $entity */
        $entity = $em->getRepository('VipaJournalBundle:JournalTheme')->find($id);
        $this->throw404IfNotFound($entity);

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_journal_theme'.$entity->getId());

        return $this->render(
            'VipaJournalBundle:Theme:show.html.twig',
            array(
                'entity' => $entity,
                'token'  => $token,
            )
        );
    }

    /**
     * Displays a form to edit an existing Theme entity.
     *
     * @param  integer  $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $journal = $this->get('vipa.journal_service')->getSelectedJournal();

        if (!$this->isGranted('EDIT', $journal, 'theme')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        /** @var JournalTheme $entity */
        $entity = $em->getRepository('VipaJournalBundle:JournalTheme')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'VipaJournalBundle:Theme:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Theme entity.
     *
     * @param JournalTheme $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(JournalTheme $entity)
    {
        $form = $this->createForm(
            new JournalThemeType(),
            $entity,
            array(
                'action' => $this->generateUrl('vipa_journal_theme_update', array('id' => $entity->getId(), 'journalId' => $entity->getJournal()->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Theme entity.
     *
     * @param  Request                   $request
     * @param  integer                   $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $journal = $this->get('vipa.journal_service')->getSelectedJournal();

        if (!$this->isGranted('EDIT', $journal, 'theme')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }

        /** @var JournalTheme $entity */
        $entity = $em->getRepository('VipaJournalBundle:JournalTheme')->find($id);
        $this->throw404IfNotFound($entity);

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');
            return $this->redirectToRoute('vipa_journal_theme_edit', ['id' => $entity->getId(), 'journalId' => $journal->getId()]);
        }

        return $this->render(
            'VipaJournalBundle:Theme:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request                                      $request
     * @param  integer                                      $id
     * @return RedirectResponse
     * @throws TokenNotFoundException|AccessDeniedException
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $journal = $this->get('vipa.journal_service')->getSelectedJournal();

        if (!$this->isGranted('DELETE', $journal, 'theme')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }

        /** @var JournalTheme $entity */
        $entity = $em->getRepository('VipaJournalBundle:JournalTheme')->find($id);
        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('vipa_journal_theme'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $this->get('vipa_core.delete.service')->check($entity);
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');
        return $this->redirectToRoute('vipa_journal_theme_index', ['journalId' => $journal->getId()]);
    }

    /**
     * @link {https://github.com/APY/APYDataGridBundle/blob/master/Resources/doc/grid_configuration/multi_grid_manager.md}
     * @return Response
     */
    public function globalThemesAction()
    {
        //disable journal filter for get all journal themes
        $GLOBALS['Vipa\JournalBundle\Entity\JournalTheme#journalFilter'] = false;
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'theme')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $grid1 = $this->get('grid');
        $grid2 = $this->get('grid');

        $source1 = new Entity('VipaAdminBundle:AdminJournalTheme');
        $tableAlias1 = $source1->getTableAlias();
        $source1->manipulateQuery(
            function ($query) use ($tableAlias1) {
                $query->andWhere($tableAlias1 . '.public = true');
            }
        );

        $source2 = new Entity('VipaJournalBundle:JournalTheme');
        $tableAlias2 = $source2->getTableAlias();
        $source2->manipulateQuery(
            function ($query) use ($tableAlias2, $journal) {
                $query
                    ->andWhere($tableAlias2 . '.public = true')
                    ->andWhere($tableAlias2 . '.journal != :journal')
                    ->setParameter('journal' , $journal);
            }
        );

        $grid1->setSource($source1);
        $gridAction1 = $this->get('grid_action');
        $actionColumn1 = new ActionsColumn("actions", 'actions');
        $rowAction1[] = $gridAction1->cloneThemeAction('vipa_journal_global_theme_clone', [
            'id',
            'journalId' => $journal->getId(),
            'type' => 'global'
        ]);
        $rowAction1[] = $gridAction1->cloneThemeAction('vipa_journal_global_theme_clone', [
            'id',
            'journalId' => $journal->getId(),
            'type' => 'global',
            'use'
        ], null, [
            'icon' => 'css3',
            'title' => 'clone.and.use'
        ]);
        $rowAction1[] = $gridAction1->themePreviewAction('vipa_journal_index', [
            'id',
            'publisher' => $journal->getPublisher()->getSlug(),
            'slug' => $journal->getSlug(),
            'type' => 'global',
            'themePreview'
        ]);
        $actionColumn1->setRowActions($rowAction1);
        $grid1->addColumn($actionColumn1);

        $grid2->setSource($source2);
        $gridAction2 = $this->get('grid_action');
        $actionColumn2 = new ActionsColumn("actions", 'actions');
        $rowAction2[] = $gridAction2->cloneThemeAction('vipa_journal_global_theme_clone', [
            'id',
            'journalId' => $journal->getId(),
            'type' => 'journal'
        ]);
        $rowAction2[] = $gridAction2->cloneThemeAction('vipa_journal_global_theme_clone', [
            'id',
            'journalId' => $journal->getId(),
            'type' => 'journal',
            'use'
        ], null, [
            'icon' => 'css3',
            'title' => 'clone.and.use'
        ]);
        $rowAction2[] = $gridAction2->themePreviewAction('vipa_journal_index', [
            'id',
            'publisher' => $journal->getPublisher()->getSlug(),
            'slug' => $journal->getSlug(),
            'type' => 'journal',
            'themePreview'
        ]);
        $actionColumn2->setRowActions($rowAction2);
        $grid2->addColumn($actionColumn2);

        if ($grid1->isReadyForRedirect() || $grid2->isReadyForRedirect())
        {
            if ($grid1->isReadyForExport()) {
                return $grid1->getExportResponse();
            }
            if ($grid2->isReadyForExport()) {
                return $grid2->getExportResponse();
            }
            // Url is the same for the grids
            return new RedirectResponse($grid1->getRouteUrl());
        } else {
            return $this->render('VipaJournalBundle:Theme:global_themes.html.twig', array(
                'globalThemesGrid' => $grid1,
                'globalJournalThemesGrid' => $grid2));
        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function cloneGlobalThemeAction(Request $request, $id)
    {
        $useTheme = $request->query->has('use') ? true: false;
        $themeType = $request->get('type');
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'theme')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $em = $this->getDoctrine()->getManager();
        $theme = null;
        if($themeType == 'journal'){
            //disable journal filter for get all journal themes
            $GLOBALS['Vipa\JournalBundle\Entity\JournalTheme#journalFilter'] = false;
            $theme = $em->getRepository('VipaJournalBundle:JournalTheme')->find($id);
        }elseif($themeType == 'global'){
            $theme = $em->getRepository('VipaAdminBundle:AdminJournalTheme')->find($id);
        }
        $this->throw404IfNotFound($theme);
        $clonedTheme = new JournalTheme();
        $clonedTheme
            ->setJournal($journal)
            ->setTitle($theme->getTitle().' [cloned]')
            ->setCss($theme->getCss())
            ->setPublic(false)
            ;
        $em->persist($clonedTheme);
        $em->flush();
        if($useTheme){
            $journal->setTheme($clonedTheme);
            $em->persist($journal);
            $em->flush();
            $this->successFlashBag('successfully.cloned.global.theme.and.used');
        }else{
            $this->successFlashBag('successfully.cloned.global.theme');
        }
        return $this->redirectToRoute('vipa_journal_theme_index', [
            'journalId' => $journal->getId()
        ]);
    }
}
