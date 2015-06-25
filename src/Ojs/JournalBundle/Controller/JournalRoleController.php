<?php

namespace Ojs\JournalBundle\Controller;

use Doctrine\ORM\Query;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\EntityManager;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalRole;
use Ojs\JournalBundle\Form\Type\JournalRoleType;
use Ojs\UserBundle\Entity\Role;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * JournalRole controller.
 *
 */
class JournalRoleController extends Controller
{
    /**
     * Lists all JournalRole entities.
     *
     */
    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }

        $source = new Entity('OjsJournalBundle:JournalRole');
        $source->addHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');
        $actionColumn = new ActionsColumn("actions", "actions");
        $rowAction[] = $gridAction->switchUserAction('ojs_public_index', ['user.username'], null, 'user.username');
        $rowAction[] = $gridAction->showAction('ujr_show', 'id');
        $rowAction[] = $gridAction->editAction('ujr_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ujr_delete', 'id');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:JournalRole:index.html.twig', $data);
    }

    /**
     * Creates a new JournalRole entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $em = $this->getDoctrine()->getManager();
        $entity = new JournalRole();
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

            $this->successFlashBag('successful.create');

            return $this->redirectToRoute(
                'ujr_show',
                [
                    'id' => $entity->getId(),
                ]
            );
        }

        return $this->render(
            'OjsJournalBundle:JournalRole:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a JournalRole entity.
     *
     * @param JournalRole $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(JournalRole $entity)
    {
        $form = $this->createForm(
            new JournalRoleType(),
            $entity,
            array(
                'action' => $this->generateUrl('ujr_create'),
                'method' => 'POST',
                'usersEndPoint' => $this->generateUrl('api_get_users'),
                'userEndPoint' => $this->generateUrl('api_get_user'),
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'row btn btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new JournalRole entity.
     *
     */
    public function newAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $entity = new JournalRole();
        $entity->setJournal($journal);
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsJournalBundle:JournalRole:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a JournalRole entity.
     *
     * @param  integer  $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $entity = $em->getRepository('OjsJournalBundle:JournalRole')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );
        $this->throw404IfNotFound($entity);

        return $this->render(
            'OjsJournalBundle:JournalRole:show.html.twig',
            array(
                'entity' => $entity,
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
     * @param  string   $tpl
     * @return Response
     */
    public function showJournalsOfUserAction($user_id, $tpl = 'show_journals.html.twig')
    {
        /**@todo we can do some permission checks */
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("OjsJournalBundle:JournalRole")->findBy(['userId' => $user_id]);
        $_data = [];
        foreach ($entities as $entity) {
            /** @var JournalRole $entity */
            $_data[$entity->getJournalId()]['roles'][] = $entity->getRole();
            $_data[$entity->getJournalId()]['user'] = $entity->getUser();
            $_data[$entity->getJournalId()]['journal'] = $entity->getJournal();
            $_data[$entity->getJournalId()]['id'] = $entity->getId();
        }

        return $this->render(
            'OjsJournalBundle:JournalRole:'.$tpl,
            array(
                'entities' => $_data,
            )
        );
    }

    /**
     * Displays a form to edit an existing JournalRole entity.
     *
     * @param  integer  $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $entity = $em->getRepository('OjsJournalBundle:JournalRole')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );
        $this->throw404IfNotFound($entity);

        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsJournalBundle:JournalRole:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a JournalRole entity.
     *
     * @param JournalRole $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(JournalRole $entity)
    {
        $form = $this->createForm(
            new JournalRoleType($this->container),
            $entity,
            array(
                'action' => $this->generateUrl('ujr_update', array('id' => $entity->getId())),
                'method' => 'PUT',
                'usersEndPoint' => $this->generateUrl('api_get_users'),
                'userEndPoint' => $this->generateUrl('api_get_user'),
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing JournalRole entity.
     *
     * @param  Request                   $request
     * @param  integer                   $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $entity = $em->getRepository('OjsJournalBundle:JournalRole')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );
        $this->throw404IfNotFound($entity);

        /** @var EntityManager $em */
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $newEntity = new JournalRole();
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
            'OjsJournalBundle:JournalRole:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a JournalRole entity.
     *
     * @param  integer          $id
     * @return RedirectResponse
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('DELETE', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $entity = $em->getRepository('OjsJournalBundle:JournalRole')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );
        $this->throw404IfNotFound($entity);

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
     * @param  null|int                  $journalId
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
            $ujr = $doc->getRepository('OjsJournalBundle:JournalRole')->findOneBy(
                array(
                    'userId' => $user->getId(),
                    'journalId' => $journal->getId(),
                    'roleId' => $role->getId(),
                )
            );
            $ujr = !$ujr ? new JournalRole() : $ujr;
            $ujr->setUser($user);
            $ujr->setJournal($journal);
            $ujr->setRole($role);
            $em->persist($ujr);
            $em->flush();

            return $this->redirect($this->generateUrl('user_join_journal'));
        }
        /** @var Journal[] $myJournals */
        $myJournals = $doc->getRepository('OjsJournalBundle:JournalRole')
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
     * @param  Journal          $journal
     * @param  Role             $role
     * @return RedirectResponse
     */
    public function leaveAction(Journal $journal, Role $role)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        /** @var JournalRole $ujr */
        $ujr = $em->getRepository('OjsJournalBundle:JournalRole')->findOneBy(
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
