<?php

namespace Ojs\JournalBundle\Controller;

use Doctrine\ORM\Query;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Elastica\Exception\NotFoundException;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalDesign;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;
use Ojs\JournalBundle\Form\Type\JournalDesignType;
use Ojs\JournalBundle\Entity\Journal;

/**
 * JournalDesign controller.
 *
 */
class JournalDesignController extends Controller
{

    /**
     * Lists all JournalDesign entities.
     *
     */
    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for view this journal's designs!");
        }
        $source = new Entity('OjsJournalBundle:JournalDesign');
        $source->addHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');
        $tableAlias = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $query) use ($tableAlias, $journal) {
                $query->andWhere($tableAlias.'.journal = :journal')
                    ->setParameter('journal', $journal);
            }
        );
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('ojs_journal_design_show', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->editAction('ojs_journal_design_edit', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->deleteAction('ojs_journal_design_delete', ['id', 'journalId' => $journal->getId()]);

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:JournalDesign:index.html.twig', $data);
    }

    /**
     * Creates a new JournalDesign entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for create a this journal's design!");
        }
        $entity = new JournalDesign();
        $form = $this->createCreateForm($entity, $journal);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setContent(
                $this->prepareDesignContent($entity->getEditableContent())
            );
            $entity->setJournal($journal);
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('ojs_journal_design_show', ['id' => $entity->getId(), 'journalId' => $journal->getId()]);
        }
        return $this->render(
            'OjsJournalBundle:JournalDesign:new.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @param Journal $journal
     * @return Form
     */
    private function createCreateForm(JournalDesign $entity, Journal $journal)
    {
        $form = $this->createForm(
            new JournalDesignType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_journal_design_create', ['journalId' => $journal->getId()]),
                'method' => 'POST',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new JournalDesign entity.
     *
     * @return Response
     */
    public function newAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for create a this journal's design!");
        }
        $entity = new JournalDesign();
        $form = $this->createCreateForm($entity, $journal);

        return $this->render(
            'OjsJournalBundle:JournalDesign:new.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for view this journal's design!");
        }
        /** @var JournalDesign $design */
        $design = $em->getRepository('OjsJournalBundle:JournalDesign')->find($id);
        $this->throw404IfNotFound($design);
        if($design->getJournal()->getId() !== $journal->getId())
        {
            throw new NotFoundException("Journal Design not found!");
        }
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_design'.$id);

        return $this->render(
            'OjsJournalBundle:JournalDesign:show.html.twig',
            array(
                'entity' => $design,
                'token'  => $token,
            )
        );
    }

    /**
     *
     * @param  integer  $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's design!");
        }

        $editForm = $this->createEditForm();

        return $this->render(
            'OjsJournalBundle:JournalDesign:edit.html.twig',
            array(
                'designId' => $id,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     *
     * @return Form The form
     */
    private function createEditForm()
    {
        $form = $this->createForm(
            new JournalDesignType(),
            array(
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     *
     * @param  Request                   $request
     * @param  integer                   $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for view this journal's sections!");
        }

        $editForm = $this->createEditForm();
        $editForm->handleRequest($request);

        /*
         * @TODO
         * $editForm->isValid() returns false even when the form is valid
         */
        if ($editForm->isValid() && $id != $request->get('ojs_journalbundle_journaldesign')['design']) {
            $design = $em->getRepository('OjsJournalBundle:Design')->find($id);
            $newDesign = $em->getRepository('OjsJournalBundle:Design')->find(
                $request->get('ojs_journalbundle_journaldesign')['design']
            );

            $journal->removeDesign($design);
            $journal->addDesign($newDesign);
            $em->persist($journal);
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('ojs_journal_design_edit', ['id' => $newDesign->getId(), 'journalId' => $journal->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:JournalDesign:edit.html.twig',
            array(
                'designId' => $id,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request                $request
     * @param  integer                $id
     * @return RedirectResponse
     * @throws TokenNotFoundException
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('DELETE', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for view this journal's sections!");
        }

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_journal_design'.$id);
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }

        $design = $em->getRepository('OjsJournalBundle:Design')->find($id);
        $journal->removeDesign($design);
        $em->persist($journal);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_journal_design_index', ['journalId' => $journal->getId()]);
    }

    /**
     * @param  String                                            $editableContent
     * @return String
     */
    private function prepareDesignContent($editableContent)
    {
        $editableContent = preg_replace_callback(
            '/<span\s*class\s*=\s*"\s*design-hide-block[^"]*"[^>]*>.*<\s*\/\s*span\s*>.*<span\s*class\s*=\s*"\s*design-hide-endblock[^"]*"[^>]*>.*<\s*\/\s*span\s*>/Us',
            function($matches)
            {
                preg_match('/<!--.*-->/Us', $matches[0], $matched);
                return str_ireplace(['<!--', '-->'], '', $matched[0]);
            },
            $editableContent
        );
        $editableContent = preg_replace_callback(
            '/<span\s*class\s*=\s*"\s*design-hide-span[^"]*"[^>]*>.*<\s*\/\s*span\s*>/Us',
            function($matches)
            {
                preg_match('/<!--.*-->/Us', $matches[0], $matched);
                return str_ireplace(['<!--', '-->'], '', $matched[0]);
            },
            $editableContent
        );
        $editableContent = preg_replace_callback(
            '/<span\s*class\s*=\s*"\s*design-inline[^"]*"[^>]*>.*<\s*\/\s*span\s*>/Us',
            function($matches)
            {
                preg_match('/title\s*=\s*"\s*{.*}\s*"/Us', $matches[0], $matched);
                $matched[0] = preg_replace('/title\s*=\s*"/Us', '', $matched[0]);
                return str_replace('"', '', $matched[0]);
            },
            $editableContent
        );
        $editableContent = str_replace(
            [
                '<!--gm-editable-region-->',
                '<!--/gm-editable-region-->'
            ],
            '',
            $editableContent
        );
        return $editableContent;
    }
}
