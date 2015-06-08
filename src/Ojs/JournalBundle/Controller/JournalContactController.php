<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Helper\ActionHelper;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalContact;
use Ojs\JournalBundle\Form\JournalContactType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * JournalContact controller.
 *
 */
class JournalContactController extends Controller
{

    /**
     * Lists all JournalContact entities.
     * @return Response
     */
    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        /** @var User $user */
        $user = $this->getUser();
        if(!$this->isGranted('VIEW', $journal, 'contacts')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $source = new Entity('OjsJournalBundle:JournalContact');
        if ($journal) {
            $tableAlias = $source->getTableAlias();
            $source->manipulateQuery(
                    function (QueryBuilder $query) use ($tableAlias, $journal) {
                $query->andWhere($tableAlias.'.journalId = '.$journal->getId());
            }
            );
        }

        $grid = $this->get('grid');
        $grid->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction = [];
        ActionHelper::setup($this->get('security.csrf.token_manager'), $this->get('translator'));

        if ($user->isAdmin()) {
            $rowAction[] = ActionHelper::showAction('journalcontact_show', 'id');
            $rowAction[] = ActionHelper::editAction('journalcontact_edit', 'id');
            $rowAction[] = ActionHelper::deleteAction('journalcontact_delete', 'id');
        } elseif ($this->get('ojs.journal_service')->hasJournalRole('ROLE_JOURNAL_MANAGER')) {
            $rowAction[] = ActionHelper::showAction('manager_journalcontact_show', 'id');
            $rowAction[] = ActionHelper::editAction('manager_journalcontact_edit', 'id');
            $rowAction[] = ActionHelper::deleteAction('manager_journalcontact_delete', 'id');
        }
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('OjsJournalBundle:JournalContact:index.html.twig', array(
                    'grid' => $grid,
        ));
    }

    /**
     * List all contacts for current journal
     * @return Response
     */
    public function indexManagerAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        return $this->indexAction($journal);
    }

    /**
     * Creates a new JournalContact entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        /** @var User $user */
        $user = $this->getUser();
        if(!$this->isGranted('CREATE', $journal, 'contacts')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $entity = new JournalContact();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('Successfully created');

            return $this->redirectToRoute($user->isAdmin() ? 'journalcontact_show' : 'manager_journalcontact_show', [
                'id' => $entity->getId(),
                ]
            );
        }

        return $this->render('OjsJournalBundle:JournalContact:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a JournalContact entity.
     *
     * @param  JournalContact $entity       The entity
     * @param  array          $optionsArray
     * @return Form           The form
     */
    private function createCreateForm(JournalContact $entity, $optionsArray = array())
    {
        /** @var User $user */
        $user = $this->getUser();
        $options = array_merge(array(
            'action' => $this->generateUrl($user->isAdmin() ? 'journalcontact_create' : 'manager_journalcontact_create'),
            'method' => 'POST',
            'user' => $this->getUser(),
                ), $optionsArray);
        $form = $this->createForm(new JournalContactType(), $entity, $options);

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    public function newManagerAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        return $this->newAction($journal);
    }

    /**
     * Displays a form to create a new JournalContact entity.
     *
     * @return Response
     */
    public function newAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('CREATE', $journal, 'contacts')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $options['user'] = $this->getUser();
        $options['journal'] = $journal ?: null;
        $entity = new JournalContact();
        $form = $this->createCreateForm($entity, $options);

        return $this->render('OjsJournalBundle:JournalContact:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a JournalContact entity.
     * @param JournalContact $entity
     * @return Response
     */
    public function showAction(JournalContact $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('VIEW', $journal, 'contacts')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        return $this->render('OjsJournalBundle:JournalContact:show.html.twig', array(
                    'entity' => $entity
            )
        );
    }

    /**
     * Displays a form to edit an existing JournalContact entity.
     *
     * @param JournalContact $entity
     * @return Response
     */
    public function editAction(JournalContact $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('EDIT', $journal, 'contacts')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $editForm = $this->createEditForm($entity);
        return $this->render('OjsJournalBundle:JournalContact:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a JournalContact entity.
     *
     * @param JournalContact $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(JournalContact $entity)
    {
        $form = $this->createForm(new JournalContactType(), $entity, array(
            'action' => $this->generateUrl('journalcontact_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'user' => $this->getUser(),
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing JournalContact entity.
     *
     * @param Request $request
     * @param JournalContact $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, JournalContact $entity)
    {
        /** @var User $user */
        $user = $this->getUser();
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('EDIT', $journal, 'contacts')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }

        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('Successfully updated');

            return $this->redirectToRoute($user->isAdmin() ? 'journalcontact_edit' : 'manager_journalcontact_edit', [
                'id' => $entity->getId(),
                ]
            );
        }

        return $this->render('OjsJournalBundle:JournalContact:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a JournalContact entity.
     * @param Request $request
     * @param JournalContact $entity
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, JournalContact $entity)
    {
        /** @var User $user */
        $user = $this->getUser();
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('DELETE', $journal, 'contacts')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        if ($user->isAdmin()) {
            $token = $csrf->getToken('journalcontact'.$entity->getId());
        } elseif ($this->get('ojs.journal_service')->hasJournalRole('ROLE_JOURNAL_MANAGER')) {
            $token = $csrf->getToken('manager_journalcontact'.$entity->getId());
        }else{
            $token = $csrf->getToken('journalcontact'.$entity->getId());
        }

        if($token!=$request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('Successfully removed');

        return $this->redirectToRoute($user->isAdmin() ? 'journalcontact' : 'manager_journalcontact');
    }
}
