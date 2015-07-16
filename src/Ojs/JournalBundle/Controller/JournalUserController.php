<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Form\Type\JournalUserType;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalUser;
use Ojs\JournalBundle\Form\Type\JournalNewUserType;
use Ojs\UserBundle\Entity\Role;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;

/**
 * JournalUser controller.
 *
 */
class JournalUserController extends Controller
{
    /**
     * Finds and displays a Users of a Journal with roles
     * @return mixed
     */
    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }

        $source = new Entity('OjsJournalBundle:JournalUser');
        $source->addHint(Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');

        $alias = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $qb) use ($journal, $alias) {
                $qb->andWhere($alias . '.journal = :journal')
                    ->setParameter('journal', $journal);
            }
        );

        $grid = $this->get('grid');
        $grid->setSource($source);
        $gridAction = $this->get('grid_action');

        $rowAction = [];
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
     * Creates a new User entity.
     *
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function createUserAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $entity = new User();
        $form = $this->createCreateForm($entity, $journal->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $formData = $form->getData();
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($entity);
            $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $entity->setPassword($password);
            $entity->setAvatar($request->get('user_avatar'));
            $em->persist($entity);

            $journalUser = new JournalUser();
            $journalUser->setUser($entity);
            $journalUser->setJournal($journal);

            if (count($formData->getJournalRoles()) > 0) {
                $journalUser->setRoles($formData->getJournalRoles());
            }

            $em->persist($journalUser);

            $em->flush();
            $this->successFlashBag('successful.create');
            return $this->redirectToRoute('ojs_journal_user_index', ['journalId' => $journal->getId()]);
        }

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
     * @param   User    $entity
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

        return $form;
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
                ->findOneBy(['user' => $entity->getUser(), 'journal' => $entity->getJournal()]);

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
                'method' => 'POST',
                'usersEndpoint' => $this->generateUrl('api_get_users'),
                'userEndpoint' => $this->generateUrl('get_user_by_id'),
            )
        );

        return $form;
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
                ->findOneBy(['user' => $user, 'journal' => $journal]
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

            return $this->redirect($this->generateUrl('ojs_journal_user_register_list'));
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
        $user = $this->getUser();
        $journalUsers = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:JournalUser')
            ->findBy(['user' => $user]);

        return $this->render('OjsJournalBundle:JournalUser:journals.html.twig', ['journalUsers' => $journalUsers]);
    }

    public function leaveJournalAction($journalId, $roleId = null)
    {
        $user = $this->getUser();
        $journal = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:Journal')
            ->find($journalId);
        $journalUser = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:JournalUser')
            ->findOneBy(['user' => $user, 'journal' => $journal]);

        $this->throw404IfNotFound($journalUser);
        $em = $this->getDoctrine()->getEntityManager();

        if ($roleId == null) {
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
}
