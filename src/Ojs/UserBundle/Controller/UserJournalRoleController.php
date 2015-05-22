<?php

namespace Ojs\UserBundle\Controller;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Helper\ActionHelper;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\UserBundle\Entity\Role;
use Ojs\UserBundle\Entity\UserJournalRole;
use Ojs\UserBundle\Form\UserJournalRoleType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ojs\Common\Controller\OjsController as Controller;

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
        $source = new Entity('OjsUserBundle:UserJournalRole');
        $grid = $this->get('grid')->setSource($source);
        $actionColumn = new ActionsColumn("actions", "actions");
        $rowAction = [];
        $rowAction[] = ActionHelper::switchUserAction('ojs_public_index', ['user.username'], 'ROLE_SUPER_ADMIN', 'user.username');
        $rowAction[] = ActionHelper::showAction('ujr_show', 'id');
        $rowAction[] = ActionHelper::editAction('ujr_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('ujr_delete', 'id');
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
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
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

            return $this->redirectToRoute('ujr_show', [
                'id' => $entity->getId(),
                ]
            );
        }

        return $this->render('OjsUserBundle:UserJournalRole:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
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
        $form = $this->createForm(new UserJournalRoleType($this->container), $entity, array(
            'action' => $this->generateUrl('ujr_create'),
            'method' => 'POST',
            'user' => $this->getUser(),
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'row btn btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new UserJournalRole entity.
     *
     */
    public function newAction()
    {
        $journal = $this->container->get("ojs.journal_service")->getSelectedJournal();
        $entity = new UserJournalRole();
        $entity->setJournal($journal);
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $form = $this->createCreateForm($entity);

        return $this->render('OjsUserBundle:UserJournalRole:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a UserJournalRole entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsUserBundle:UserJournalRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        return $this->render('OjsUserBundle:UserJournalRole:show.html.twig', array(
            'entity' => $entity,
            )
        );
    }

    /**
     * Finds and displays a Users of a Journal with roles  (ungrouped).
     * @param $journal_id
     * @return Response
     */
    public function showUsersOfJournalAction($journal_id)
    {
        $source = new Entity('OjsUserBundle:UserJournalRole');
        $ta = $source->getTableAlias();
        $source->manipulateQuery(function (QueryBuilder $qb) use ($journal_id, $ta) {
            $qb->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->eq($ta.'.journalId', ':jid')
                )
            )->setParameter('jid', $journal_id);
        });
        $grid = $this->get('grid');

        $grid->setSource($source);

        $rowAction = new RowAction('<i class="fa fa-envelope-o"></i>', 'user_send_mail');
        $rowAction->setAttributes(['class' => 'btn-xs btn btn-primary']);
        $rowAction->setRouteParameters(['id']);
        $rowAction->setRouteParametersMapping([
            'id' => 'user',
        ]);
        $rowAction->setColumn('actions');
        $column = new ActionsColumn('actions', 'user.journalrole.send_email');
        $column->setSafe(false);

        $grid->addColumn($column);
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('OjsUserBundle:UserJournalRole:show_users.html.twig', array(
            'grid' => $grid,
        ));
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

        return $this->render('OjsUserBundle:UserJournalRole:'.$tpl, array(
            'entities' => $_data,
        ));
    }

    /**
     * Displays a form to edit an existing UserJournalRole entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var UserJournalRole $entity */
        $entity = $em->getRepository('OjsUserBundle:UserJournalRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('OjsUserBundle:UserJournalRole:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
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
        $form = $this->createForm(new UserJournalRoleType($this->container), $entity, array(
            'action' => $this->generateUrl('ujr_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'user' => $this->getUser(),
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing UserJournalRole entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var UserJournalRole $entity */
        $entity = $em->getRepository('OjsUserBundle:UserJournalRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $newEntity = new UserJournalRole();
            $newEntity->setJournal($entity->getJournal())
                ->setRole($entity->getRole())
                ->setUser($entity->getUser())
            ;
            $em->remove($entity);
            $em->persist($newEntity);
            $em->flush();

            $this->successFlashBag('Successfully updated');

            return $this->redirectToRoute('ujr_edit', [
                'id' => $newEntity->getId(),
                ]
            );
        }

        return $this->render('OjsUserBundle:UserJournalRole:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a UserJournalRole entity.
     * @param  UserJournalRole  $entity
     * @return RedirectResponse
     */
    public function deleteAction(UserJournalRole $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ujr');
    }

    /**
     * @param  Journal          $journal
     * @param  Role             $role
     * @return RedirectResponse
     */
    public function leaveAction(Journal $journal, Role $role)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        /** @var UserJournalRole $ujr */
        $ujr = $em->getRepository('OjsUserBundle:UserJournalRole')->findOneBy(['journal' => $journal, 'role' => $role, 'user' => $user]);
        if (!$ujr) {
            throw new NotFoundHttpException();
        }
        $journal->removeUserRole($ujr);
        $em->persist($journal);
        $em->flush();

        return $this->redirect($this->get('router')->generate('user_show_my_journals'));
    }
}
