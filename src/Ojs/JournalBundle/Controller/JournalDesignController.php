<?php

namespace Ojs\JournalBundle\Controller;

use Doctrine\ORM\Query;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalDesign;
use Ojs\JournalBundle\Form\Type\JournalDesignType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;

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
        $ta = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $qb) use ($journal, $ta) {
                $qb->andWhere($ta.'.journal = :journal')
                    ->setParameter('journal', $journal);
            }
        );
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('admin_journaldesign_show', 'id');
        $rowAction[] = $gridAction->editAction('admin_journaldesign_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('admin_journaldesign_delete', 'id');

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
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setJournal($journal);
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('admin_journaldesign_show', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:JournalDesign:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a JournalDesign entity.
     *
     * @param JournalDesign $entity The entity
     *
     * @return Form The form
     */
    private function createCreateForm(JournalDesign $entity)
    {
        $form = $this->createForm(
            new JournalDesignType(),
            $entity,
            array(
                'action' => $this->generateUrl('admin_journaldesign_create'),
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
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsJournalBundle:JournalDesign:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a JournalDesign entity.
     * @param  integer  $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'design')) {
            throw new AccessDeniedException("You are not authorized for view this journal's design!");
        }
        $entity = $em->getRepository('OjsJournalBundle:JournalDesign')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );
        $this->throw404IfNotFound($entity);

        return $this->render(
            'OjsJournalBundle:JournalDesign:show.html.twig',
            array(
                'entity' => $entity,
            )
        );
    }

    /**
     * Displays a form to edit an existing JournalDesign entity.
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
        /** @var JournalDesign $entity */
        $entity = $em->getRepository('OjsJournalBundle:JournalDesign')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );
        $this->throw404IfNotFound($entity);

        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsJournalBundle:JournalDesign:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a JournalDesign entity.
     *
     * @param JournalDesign $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(JournalDesign $entity)
    {
        $form = $this->createForm(
            new JournalDesignType(),
            $entity,
            array(
                'action' => $this->generateUrl('admin_journaldesign_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing JournalDesign entity.
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
        /** @var JournalDesign $entity */
        $entity = $em->getRepository('OjsJournalBundle:JournalDesign')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );
        $this->throw404IfNotFound($entity);

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('admin_journaldesign_edit', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:JournalDesign:edit.html.twig',
            array(
                'entity' => $entity,
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
        /** @var JournalDesign $entity */
        $entity = $em->getRepository('OjsJournalBundle:JournalDesign')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );
        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('admin_journaldesign'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('admin_JournalDesign');
    }
}
