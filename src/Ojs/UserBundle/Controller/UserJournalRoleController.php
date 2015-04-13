<?php

namespace Ojs\UserBundle\Controller;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Mapping\Column;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Helper\ActionHelper;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\UserBundle\Entity\Role;
use Ojs\UserBundle\Entity\UserJournalRole;
use Ojs\UserBundle\Form\UserJournalRoleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $rowAction[] = ActionHelper::switchUserAction('ojs_public_index', ['username'], 'ROLE_SUPER_ADMIN');
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
     */
    public function createAction(Request $request)
    {
        $entity = new UserJournalRole();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneById($data->getJournalId());
            $user = $em->getRepository('OjsUserBundle:User')->findOneById($data->getUserId());
            $role = $em->getRepository('OjsUserBundle:Role')->findOneById($data->getRoleId());
            $entity->setUser($user);
            $entity->setJournal($journal);
            $entity->setRole($role);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ujr_show', array('id' => $entity->getId())));
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
        $form = $this->createForm(new UserJournalRoleType(), $entity, array(
            'action' => $this->generateUrl('ujr_create'),
            'method' => 'POST',
            'user' => $this->getUser()
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
        $entity = new UserJournalRole();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsUserBundle:UserJournalRole:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a UserJournalRole entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsUserBundle:UserJournalRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserJournalRole entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsUserBundle:UserJournalRole:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Creates a form to delete a UserJournalRole entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ujr_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }

    /**
     * Finds and displays a Users of a Journal with roles  (ungrouped).
     * @param int $journal_id
     */
    public function showUsersOfJournalAction($journal_id)
    {
        $source = new Entity('OjsUserBundle:UserJournalRole');
        $ta = $source->getTableAlias();
        $source->manipulateQuery(function (QueryBuilder $qb) use ($journal_id, $ta) {
            $qb->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->eq($ta . '.journalId', ':jid')
                )
            )->setParameter('jid', $journal_id);
        });
        $grid = $this->get('grid');

        $grid->setSource($source);

        $rowAction = new RowAction('<i class="fa fa-envelope-o"></i>', 'user_send_mail');
        $rowAction->setAttributes(['class' => 'btn-xs btn btn-primary']);
        $rowAction->setRouteParameters(['id']);
        $rowAction->setRouteParametersMapping([
            'id' => 'user'
        ]);
        $rowAction->setColumn('actions');
        $column = new ActionsColumn('actions', 'user.journalrole.send_email');
        $column->setSafe(false);

        $grid->addColumn($column);
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('OjsUserBundle:UserJournalRole:show_users.html.twig', array(
            'grid' => $grid
        ));
    }

    public function myJournalsAction()
    {
        $user_id = $this->getUser()->getId();

        return $this->showJournalsOfUserAction($user_id, 'show_my_journals.html.twig');
    }

    /**
     * Finds and displays a Journals of a user with roles.
     * @param mixed $journal_id
     */
    public function showJournalsOfUserAction($user_id, $tpl = 'show_journals.html.twig')
    {
        $em = $this->getDoctrine()->getManager();
        $data = $em->createQuery(
            'SELECT  u  FROM OjsUserBundle:UserJournalRole u WHERE u.userId = :user_id '
        )->setParameter('user_id', $user_id)->getResult();
        $entities = array();
        if ($data) {
            foreach ($data as $item) {
                $entities[$item->getJournalId()]['journal'] = $item->getJournal();
                $entities[$item->getJournalId()]['roles'][] = $item->getRole();
            }
        }

        return $this->render('OjsUserBundle:UserJournalRole:' . $tpl, array(
            'entities' => $entities
        ));
    }

    /**
     * Displays a form to edit an existing UserJournalRole entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsUserBundle:UserJournalRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserJournalRole entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsUserBundle:UserJournalRole:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a UserJournalRole entity.
     *
     * @param UserJournalRole $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(UserJournalRole $entity)
    {
        $form = $this->createForm(new UserJournalRoleType(), $entity, array(
            'action' => $this->generateUrl('ujr_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'user' => $this->getUser()
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing UserJournalRole entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var UserJournalRole $entity */
        $entity = $em->getRepository('OjsUserBundle:UserJournalRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserJournalRole entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $newEntity=new UserJournalRole();
            $newEntity->setJournal($entity->getJournal())
                ->setRole($entity->getRole())
                ->setUser($entity->getUser())
            ;
            $em->remove($entity);
            $em->persist($newEntity);
            $em->flush();

            return $this->redirect($this->generateUrl('ujr_edit', array('id' => $newEntity->getId())));
        }

        return $this->render('OjsUserBundle:UserJournalRole:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a UserJournalRole entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:UserJournalRole')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserJournalRole entity.');
        }
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('ujr'));
    }

    public function leaveAction(Journal $journal, Role $role)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $ujr = $em->getRepository('OjsUserBundle:UserJournalRole')->findOneBy(['journal' => $journal, 'role' => $role, 'user' => $user]);
        if(!$ujr)
            throw new NotFoundHttpException;
        $journal->removeUserRole($ujr);
        $em->persist($journal);
        $em->flush();
        return $this->redirect($this->get('router')->generate('user_show_my_journals'));
    }
}
