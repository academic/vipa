<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Elastica\Exception\NotFoundException;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\Design;
use Ojs\JournalBundle\Form\Type\DesignType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;
use APY\DataGridBundle\Grid\Row;

/**
 * Design controller.
 *
 */
class DesignController extends Controller
{
    /**
     * Lists all Design entities.
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for view this journal's designs!");
        }
        $source = new Entity('OjsJournalBundle:Design');
        $source->manipulateRow(
            function (Row $row) use ($request) {
                /* @var Design $entity */
                $entity = $row->getEntity();
                if(!is_null($entity)){
                    $entity->getOwner()->setDefaultLocale($request->getDefaultLocale());
                    $row->setField('owner', $entity->getOwner()->getTitle());
                }
                return $row;
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

        return $grid->getGridResponse('OjsJournalBundle:Design:index.html.twig', $data);
    }

    /**
     * Creates a new Design entity.
     *
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for create a this journal's design!");
        }
        $entity = new Design();
        $form = $this->createCreateForm($entity, $journal);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setContent(
                $this->prepareDesignContent($entity->getEditableContent())
            );
            $entity->setOwner($journal);
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('successful.create');
            return $this->redirectToRoute(
                'ojs_journal_design_show',
                ['id' => $entity->getId(), 'journalId' => $journal->getId()]
            );
        }

        return $this->render(
            'OjsJournalBundle:Design:new.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @param Design $entity
     * @param Journal $journal
     * @return Form
     */
    private function createCreateForm(Design $entity, Journal $journal)
    {
        $form = $this->createForm(
            new DesignType(),
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
     * Displays a form to create a new Design entity.
     *
     * @return Response
     */
    public function newAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for create a this journal's design!");
        }
        $entity = new Design();
        $form = $this->createCreateForm($entity, $journal);

        return $this->render(
            'OjsJournalBundle:Design:new.html.twig',
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
        /** @var Design $design */
        $design = $em->getRepository('OjsJournalBundle:Design')->find($id);
        $this->throw404IfNotFound($design);
        if ($design->getOwner() !== $journal) {
            throw new NotFoundException("Journal Design not found!");
        }
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_design'.$id);

        return $this->render(
            'OjsJournalBundle:Design:show.html.twig',
            array(
                'entity' => $design,
                'token' => $token,
            )
        );
    }

    /**
     *
     * @param  Design $design
     * @return Response
     */
    public function editAction(Design $design)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's design!");
        }
        $design->setEditableContent($this->prepareEditContent($design->getEditableContent()));
        $editForm = $this->createEditForm($design, $journal);

        return $this->render(
            'OjsJournalBundle:Design:edit.html.twig',
            array(
                'journal' => $journal,
                'entity' => $design,
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
     * @param Design $entity
     * @param Journal $journal
     * @return Form
     */
    private function createEditForm(Design $entity, Journal $journal)
    {
        $form = $this->createForm(
            new DesignType(),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'ojs_journal_design_update',
                    [
                        'journalId' => $journal->getId(),
                        'id' => $entity->getId()
                    ]
                ),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     *
     * @param  Request $request
     * @param  Design $design
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, Design $design)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for view this journal's sections!");
        }

        $editForm = $this->createEditForm($design, $journal);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $design->setContent(
                $this->prepareDesignContent($design->getEditableContent())
            );
            $em->flush();

            $this->successFlashBag('successful.update');
            return $this->redirectToRoute(
                'ojs_journal_design_edit',
                ['id' => $design->getId(), 'journalId' => $journal->getId()]
            );
        }

        return $this->render(
            'OjsJournalBundle:Design:edit.html.twig',
            array(
                'journal' => $journal,
                'entity' => $design,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request $request
     * @param  integer $id
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

        $requestedDesign = $em->getRepository('OjsJournalBundle:Design')->find($id);

        if ($requestedDesign->getId() === $journal->getDesign()->getId()) {
            $this->errorFlashBag('journal.design.cannot_delete_active');
        } else {
            $csrf = $this->get('security.csrf.token_manager');
            $token = $csrf->getToken('ojs_journal_design'.$id);

            if ((string)$token !== $request->get('_token')) {
                throw new TokenNotFoundException("Token Not Found!");
            }

            $em->remove($requestedDesign);
            $em->flush();

            $this->successFlashBag('successful.remove');
        }

        return $this->redirectToRoute('ojs_journal_design_index', ['journalId' => $journal->getId()]);
    }
}
