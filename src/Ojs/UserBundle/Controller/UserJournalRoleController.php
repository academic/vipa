<?php

namespace Ojs\UserBundle\Controller;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\UserBundle\Entity\Role;
use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Entity\UserJournalRole;
use Ojs\UserBundle\Form\Type\UserJournalRoleType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * UserJournalRole controller.
 *
 */
class UserJournalRoleController extends Controller
{
    /**
     * Lists all UserJournalRole entities.
     *
     */
    public function indexAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        if (!$user->isAdmin()) {
            return $this->redirectToRoute('ujr_show_users_ofjournal', ['journal_id' => $journal->getId()]);
        }
        $source = new Entity('OjsUserBundle:UserJournalRole');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');
        $actionColumn = new ActionsColumn("actions", "actions");
        $rowAction = [];

        $rowAction[] = $gridAction->switchUserAction('ojs_public_index', ['user.username'], null, 'user.username');
        $rowAction[] = $gridAction->showAction('ujr_show', 'id');
        $rowAction[] = $gridAction->editAction('ujr_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ujr_delete', 'id');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $grid->showColumns(['journal.title']);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsUserBundle:UserJournalRole:index.html.twig', $data);
    }

    /**
     * Creates a new UserJournalRole entity.
     *
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $em = $this->getDoctrine()->getManager();
        $entity = new UserJournalRole();
        $em->persist($entity);
        $form = $this->createCreateForm($entity);
        $em->clear();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $entity->setUser($data->getUser());
            $entity->setJournal($data->getJournal());
            $entity->setRole($data->getRole());
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('Successfully created');

            return $this->redirectToRoute(
                'ujr_show',
                [
                    'id' => $entity->getId(),
                ]
            );
        }

        return $this->render(
            'OjsUserBundle:UserJournalRole:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a UserJournalRole entity.
     *
     * @param UserJournalRole $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(UserJournalRole $entity)
    {
        $form = $this->createForm(
            new UserJournalRoleType($this->container),
            $entity,
            array(
                'action' => $this->generateUrl('ujr_create'),
                'method' => 'POST',
                'user' => $this->getUser(),
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'row btn btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new UserJournalRole entity.
     *
     */
    public function newAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $entity = new UserJournalRole();
        $entity->setJournal($journal);
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsUserBundle:UserJournalRole:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a UserJournalRole entity.
     *
     * @param  UserJournalRole $entity
     * @return Response
     */
    public function showAction(UserJournalRole $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }

        return $this->render(
            'OjsUserBundle:UserJournalRole:show.html.twig',
            array(
                'entity' => $entity,
            )
        );
    }

    /**
     * Finds and displays a Users of a Journal with roles  (ungrouped).
     * @return Response
     */
    public function showUsersOfJournalAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $source = new Entity('OjsUserBundle:UserJournalRole');
        $ta = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $qb) use ($journal, $ta) {
                $qb->andWhere($ta . '.journal = :journal')
                    ->setParameter('journal', $journal);
            }
        );
        $grid = $this->get('grid');

        $grid->setSource($source);

        $rowAction = new RowAction('<i class="fa fa-envelope-o"></i>', 'user_send_mail');
        $rowAction->setAttributes(['class' => 'btn-xs btn btn-primary']);
        $rowAction->setRouteParameters(['id']);
        $rowAction->setRouteParametersMapping(
            [
                'id' => 'user',
            ]
        );
        $rowAction->setColumn('actions');
        $column = new ActionsColumn('actions', 'user.journalrole.send_email');
        $column->setSafe(false);

        $grid->addColumn($column);
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse(
            'OjsUserBundle:UserJournalRole:show_users.html.twig',
            array(
                'grid' => $grid,
            )
        );
    }

    /**
     * @return Response
     */
    public function myJournalsAction()
    {
        $user_id = $this->getUser()->getId();

        return $this->showJournalsOfUserAction($user_id, 'show_my_journals.html.twig');
    }

    /**
     * Finds and displays a Journals of a user with roles.
     *
     * @param $user_id
     * @param  string $tpl
     * @return Response
     */
    public function showJournalsOfUserAction($user_id, $tpl = 'show_journals.html.twig')
    {
        /**@todo we can do some permission checks */
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("OjsUserBundle:UserJournalRole")->findBy(['userId' => $user_id]);
        $_data = [];
        foreach ($entities as $entity) {
            /** @var UserJournalRole $entity */
            $_data[$entity->getJournalId()]['roles'][] = $entity->getRole();
            $_data[$entity->getJournalId()]['user'] = $entity->getUser();
            $_data[$entity->getJournalId()]['journal'] = $entity->getJournal();
            $_data[$entity->getJournalId()]['id'] = $entity->getId();
        }

        return $this->render(
            'OjsUserBundle:UserJournalRole:' . $tpl,
            array(
                'entities' => $_data,
            )
        );
    }

    /**
     * Displays a form to edit an existing UserJournalRole entity.
     *
     * @param  UserJournalRole $entity
     * @return Response
     */
    public function editAction(UserJournalRole $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $entity->getJournal();
        if (!$this->isGranted('EDIT', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsUserBundle:UserJournalRole:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a UserJournalRole entity.
     *
     * @param UserJournalRole $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(UserJournalRole $entity)
    {
        $form = $this->createForm(
            new UserJournalRoleType($this->container),
            $entity,
            array(
                'action' => $this->generateUrl('ujr_update', array('id' => $entity->getId())),
                'method' => 'PUT',
                'user' => $this->getUser(),
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing UserJournalRole entity.
     *
     * @param  Request $request
     * @param  UserJournalRole $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, UserJournalRole $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $entity->getJournal();
        if (!$this->isGranted('EDIT', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $newEntity = new UserJournalRole();
            $newEntity->setJournal($entity->getJournal())
                ->setRole($entity->getRole())
                ->setUser($entity->getUser());
            $em->remove($entity);
            $em->persist($newEntity);
            $em->flush();

            $this->successFlashBag('Successfully updated');

            return $this->redirectToRoute(
                'ujr_edit',
                [
                    'id' => $newEntity->getId(),
                ]
            );
        }

        return $this->render(
            'OjsUserBundle:UserJournalRole:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a UserJournalRole entity.
     *
     * @param  UserJournalRole $entity
     * @return RedirectResponse
     */
    public function deleteAction(UserJournalRole $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $entity->getJournal();
        if (!$this->isGranted('DELETE', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $em = $this->getDoctrine()->getManager();

        // TODO: Protect against CSRF
        // $csrf = $this->get('security.csrf.token_manager');
        // $token = $csrf->getToken('ujr'.$entity->getId());
        // if($token!=$request->get('_token'))
        //    throw new TokenNotFoundException("Token Not Found!");

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ujr');
    }

    /**
     * @param  null|int $journalId
     * @return RedirectResponse|Response
     */
    public function registerAsAuthorAction($journalId = null)
    {
        $userId = $this->getUser()->getId();
        $doc = $this->getDoctrine();
        $em = $doc->getManager();
        // a  journal id passed so register session user as author to this journal
        if ($journalId) {
            /** @var User $user */
            $user = $doc->getRepository('OjsUserBundle:User')->find($userId);
            /** @var Journal $journal */
            $journal = $doc->getRepository('OjsJournalBundle:Journal')->find($journalId);
            /** @var Role $role */
            $role = $doc->getRepository('OjsUserBundle:Role')->findOneBy(array('role' => 'ROLE_AUTHOR'));
            // check that we have already have the link
            $ujr = $doc->getRepository('OjsUserBundle:UserJournalRole')->findOneBy(
                array(
                    'userId' => $user->getId(),
                    'journalId' => $journal->getId(),
                    'roleId' => $role->getId(),
                )
            );
            $ujr = !$ujr ? new UserJournalRole() : $ujr;
            $ujr->setUser($user);
            $ujr->setJournal($journal);
            $ujr->setRole($role);
            $em->persist($ujr);
            $em->flush();

            return $this->redirect($this->generateUrl('user_join_journal'));
        }
        /** @var Journal[] $myJournals */
        $myJournals = $doc->getRepository('OjsUserBundle:UserJournalRole')
            ->userJournalsWithRoles($userId, true); // only ids
        $entities = array();
        /** @var Journal[] $journals */
        $journals = $this->getDoctrine()->getRepository('OjsJournalBundle:Journal')->findAll();
        foreach ($journals as $journal) {
            $jid = $journal->getId();
            $roles = isset($myJournals[$jid]) ? $myJournals[$jid]['roles'] : null;
            $entities[] = array('journal' => $journal, 'roles' => $roles);
        }

        return $this->render(
            'OjsUserBundle:User:registerAuthor.html.twig',
            array(
                'entities' => $entities,
            )
        );
    }

    /**
     * Function no needs for extra acl permission checks. Because user is grabbed from session.
     *
     * @param  Journal $journal
     * @param  Role $role
     * @return RedirectResponse
     */
    public function leaveAction(Journal $journal, Role $role)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        /** @var UserJournalRole $ujr */
        $ujr = $em->getRepository('OjsUserBundle:UserJournalRole')->findOneBy(
            ['journal' => $journal, 'role' => $role, 'user' => $user]
        );
        if (!$ujr) {
            throw new NotFoundHttpException();
        }
        $journal->removeUserRole($ujr);
        $em->persist($journal);
        $em->flush();

        return $this->redirect($this->get('router')->generate('user_show_my_journals'));
    }
}
