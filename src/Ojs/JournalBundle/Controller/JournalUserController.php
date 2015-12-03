<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalUser;
use Ojs\JournalBundle\Event\JournalEvents;
use Ojs\JournalBundle\Form\Type\JournalNewUserType;
use Ojs\JournalBundle\Form\Type\JournalUserEditType;
use Ojs\JournalBundle\Form\Type\JournalUserType;
use Ojs\UserBundle\Entity\Role;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Ojs\JournalBundle\Event\JournalEvent;
use Elastica\Query as ElasticQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * JournalUser controller.
 *
 */
class JournalUserController extends Controller
{
    /**
     * Finds and displays a Users of a Journal with roles
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }

        $source = new Entity('OjsJournalBundle:JournalUser');
        $source->manipulateRow(
            function (Row $row) use ($request)
            {
                /* @var JournalUser $entity */
                $entity = $row->getEntity();
                if(!is_null($entity)){
                    $entity->getJournal()->setDefaultLocale($request->getDefaultLocale());
                    if(!is_null($entity)){
                        $row->setField('journal', $entity->getJournal()->getTitle());
                    }
                }
                return $row;
            }
        );

        $grid = $this->get('grid');
        $grid->setSource($source);
        $gridAction = $this->get('grid_action');

        $rowAction = [];
        $rowAction[] = $gridAction->editAction('ojs_journal_user_edit', ['journalId' => $journal->getId(), 'id']);
        $rowAction[] = $gridAction->deleteAction('ojs_journal_user_delete', ['journalId' => $journal->getId(), 'id']);
        $actionColumn = new ActionsColumn("actions", "actions");
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('OjsJournalBundle:JournalUser:index.html.twig', $grid);
    }

    public function newUserAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $entity = new User();
        $form = $this->createCreateForm($entity, $journal->getId());

        return $this->render(
            'OjsJournalBundle:JournalUser:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a User entity.
     * @param   integer $journalId
     * @param   User $entity
     * @return  Form    The form
     */
    private function createCreateForm(User $entity, $journalId)
    {
        $form = $this->createForm(
            new JournalNewUserType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_journal_user_create', ['journalId' => $journalId]),
                'method' => 'POST',
            )
        );
        $form->add('create', 'submit', array('label' => 'c'));

        return $form;
    }

    /**
     * Creates a new User entity.
     *
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function createUserAction(Request $request)
    {
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $entity = new User();
        $form = $this->createCreateForm($entity, $journal->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($entity);
            $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $entity->setPassword($password);
            $entity->setEnabled(true);

            $em->persist($entity);

            $journalUser = new JournalUser();
            $journalUser->setUser($entity);
            $journalUser->setJournal($journal);

            $em->persist($journalUser);
            $em->flush();

            $this->successFlashBag('successful.create');

            $event = new JournalEvent($request, $journal, $entity);
            $dispatcher->dispatch(JournalEvents::JOURNAL_USER_NEW, $event);

            return $this->redirectToRoute(
                'ojs_journal_user_edit',
                ['journalId' => $journal->getId(), 'id' => $journalUser->getId()]
            );
        }

        return $this->render(
            'OjsJournalBundle:JournalUser:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    public function addUserAction(Request $request)
    {
        /** @var Journal $journal */
        $entity = new JournalUser();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $form = $this->createAddForm($entity, $journal->getId());

        if (!$this->isGranted('CREATE', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var JournalUser $existingJournalUser */

            $em = $this->getDoctrine()->getManager();
            $entity->setJournal($journal);
            $existingJournalUser = $em
                ->getRepository('OjsJournalBundle:JournalUser')
                ->findOneBy(['user' => $entity->getUser()]);

            if ($existingJournalUser) {
                if ($existingJournalUser->getRoles()) {
                    foreach ($entity->getRoles() as $role) {
                        if (!$existingJournalUser->getRoles()->contains($role)) {
                            $existingJournalUser->getRoles()->add($role);
                        }
                    }
                } else {
                    $existingJournalUser->setRoles($entity->getRoles());
                }
                $em->persist($existingJournalUser);
            } else {
                $em->persist($entity);
            }

            $em->flush();

            $this->successFlashBag('successful.create');
            return $this->redirectToRoute('ojs_journal_user_index', ['journalId' => $journal->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:JournalUser:add.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    private function createAddForm(JournalUser $entity, $journalId)
    {
        $form = $this->createForm(
            new JournalUserType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_journal_user_add', ['journalId' => $journalId]),
                'method' => 'POST'
            )
        );

        return $form;
    }

    public function editUserAction($id)
    {
        /** @var JournalUser $entity */
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        // Although 'id' column is unique, looking for a matching journal as well is beneficial security-wise
        $entity = $em->getRepository('OjsJournalBundle:JournalUser')->find($id);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('EDIT', $journal, 'userRole')) {
            throw new AccessDeniedException("You not authorized to remove this user from the journal.");
        }

        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsJournalBundle:JournalUser:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    private function createEditForm(JournalUser $entity)
    {
        $actionUrl = $this->generateUrl('ojs_journal_user_update',
            ['journalId' => $entity->getJournal()->getId(), 'id' => $entity->getId()]);
        $form = $this->createForm(new JournalUserEditType(), $entity, ['method' => 'PUT', 'action' => $actionUrl]);
        return $form;
    }

    public function updateUserAction(Request $request, $id)
    {
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('EDIT', $journal, 'userRole')) {
            throw new AccessDeniedException("You not authorized to remove this user from the journal.");
        }

        /** @var JournalUser $entity */
        $entity = $em->getRepository('OjsJournalBundle:JournalUser')->find($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('successful.update');

            $event = new JournalEvent($request, $journal, $entity->getUser());
            $dispatcher->dispatch(JournalEvents::JOURNAL_USER_ROLE_CHANGE, $event);

            return $this->redirectToRoute('ojs_journal_user_index', ['journalId' => $journal->getId()]);
        }

        $this->errorFlashBag('error');
        return $this->redirectToRoute('ojs_journal_user_edit', ['journalId' => $journal->getId(), 'id' => $entity->getId()]);
    }

    public function deleteUserAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $entity = $em->getRepository('OjsJournalBundle:JournalUser')->find($id);

        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('DELETE', $journal, 'userRole')) {
            throw new AccessDeniedException("You not authorized to remove this user from the journal.");
        }

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_journal_user' . $id);
        if ($token->getValue() !== $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }

        foreach($entity->getRoles() as $role) {
            $entity->removeRole($role);
        }

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_journal_user_index', ['journalId' => $journal->getId()]);
    }

    /**
     * @param  null|int $journalId
     * @return RedirectResponse|Response
     */
    public function registerAsAuthorAction($journalId = null)
    {
        $user = $this->getUser();
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $role = $doctrine->getRepository('OjsUserBundle:Role')->findOneBy(['role' => 'ROLE_AUTHOR']);

        if ($journalId) {
            /**
             * @var Journal $journal
             * @var User    $user
             * @var Role    $role
             */
            $journal = $doctrine->getRepository('OjsJournalBundle:Journal')->find($journalId);

            // Check if the user is in journal already
            $journalUser = $doctrine
                ->getRepository('OjsJournalBundle:JournalUser')
                ->findOneBy(['user' => $user]
            );

            $journalUser = !$journalUser ? new JournalUser() : $journalUser;
            $journalUser->setUser($user);
            $journalUser->setJournal($journal);
            if ($journalUser->getRoles() && !$journalUser->getRoles()->contains($role)) {
                $journalUser->getRoles()->add($role);
            } else {
                $journalUser->setRoles(new ArrayCollection([$role]));
            }

            $em->persist($journalUser);
            $em->flush();

            return $this->redirectToRoute('ojs_journal_user_register_list');
        }

        /**
         * @var JournalUser[] $journalUsers
         * @var Journal[] $allJournals
         */

        $journalUsers = $doctrine
            ->getRepository('OjsJournalBundle:JournalUser')
            ->findBy(['user' => $user]);
        $allJournals = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:Journal')
            ->findAll();

        $joinedJournals = [];
        $nonJoinedJournals = [];

        foreach ($journalUsers as $journalUser) {
            $joinedJournals[] = $journalUser->getJournal();
        }

        foreach ($allJournals as $journal) {
            if (!in_array($journal, $joinedJournals)) {
                $nonJoinedJournals[] = $journal;
            }
        }

        return $this->render('OjsJournalBundle:JournalUser:register.html.twig',
            ['joined' => $joinedJournals, 'nonJoined' => $nonJoinedJournals]);
    }

    public function journalsAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException('You must login to see this page.');
        }

        $user = $this->getUser();
        $journalUsers = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:JournalUser')
            ->findBy(['user' => $user]);

        return $this->render('OjsJournalBundle:JournalUser:journals.html.twig', ['journalUsers' => $journalUsers]);
    }

    public function leaveJournalAction($roleId = null)
    {
        $user = $this->getUser();
        $journalUser = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:JournalUser')
            ->findOneBy(['user' => $user]);

        $this->throw404IfNotFound($journalUser);
        $em = $this->getDoctrine()->getManager();

        if ($roleId === null) {
            $em->remove($journalUser);
            $em->flush();
        } elseif ($journalUser->getRoles()) {
            $role = $this
                ->getDoctrine()
                ->getRepository('OjsUserBundle:Role')
                ->find($roleId);
            $journalUser->getRoles()->removeElement($role);
            $em->persist($journalUser);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ojs_journal_user_my'));
    }

    /**
     * Search users by username
     *
     * @param Request $request
     * @return array
     */
    public function getUserByUsernameAction(Request $request)
    {
        $q = $request->get('q');
        $search = $this->container->get('fos_elastica.index.search.user');

        $prefix = new ElasticQuery\Prefix();
        $prefix->setPrefix('username', strtolower($q));
        $qe = new ElasticQuery();
        $qe->setQuery($prefix);

        $results = $search->search($prefix);
        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => $result->getData()['username']." - ".$result->getData()['email'],
            ];
        }

        return JsonResponse::create($data);
    }
}
