<?php

namespace Ojstr\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojstr\UserBundle\Entity\UserJournalRole;
use Ojstr\UserBundle\Form\UserJournalRoleType;

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
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjstrUserBundle:UserJournalRole')->findAll();

        return $this->render('OjstrUserBundle:UserJournalRole:index.html.twig', array(
                    'entities' => $entities,
        ));
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
            $journal = $em->getRepository('OjstrJournalBundle:Journal')->findOneById($data->getJournalId());
            $user = $em->getRepository('OjstrUserBundle:User')->findOneById($data->getUserId());
            $role = $em->getRepository('OjstrUserBundle:Role')->findOneById($data->getRoleId());
            $entity->setUser($user);
            $entity->setJournal($journal);
            $entity->setRole($role);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ujr_show', array('id' => $entity->getId())));
        }

        return $this->render('OjstrUserBundle:UserJournalRole:new.html.twig', array(
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

        return $this->render('OjstrUserBundle:UserJournalRole:new.html.twig', array(
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

        $entity = $em->getRepository('OjstrUserBundle:UserJournalRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserJournalRole entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrUserBundle:UserJournalRole:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Finds and displays a Users of a Journal with roles  (ungrouped).
     * @param int $journal_id
     */
    public function showUsersOfJournalAction($journal_id)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->createQuery(
                        'SELECT u FROM OjstrUserBundle:UserJournalRole u WHERE u.journal_id = :jid '
                )->setParameter('jid', $journal_id);

        return $this->render('OjstrUserBundle:UserJournalRole:show_users.html.twig', array(
                    'entities' => $entities
        ));
    }

    /**
     * Finds and displays a Journals of a user with roles.
     * @param mixed $journal_id
     */
    public function showJournalsOfUserAction($user_id, $tpl = 'show_journals.html.twig')
    {
        $em = $this->getDoctrine()->getManager();
        $data = $em->createQuery(
                        'SELECT  u  FROM OjstrUserBundle:UserJournalRole u WHERE u.userId = :user_id '
                )->setParameter('user_id', $user_id)->getResult();
        if ($data) {
            $entities = array();
            foreach ($data as $item) {
                $entities[$item->getJournalId()]['journal'] = $item->getJournal();
                $entities[$item->getJournalId()]['roles'][] = $item->getRole();
            }
        }

        return $this->render('OjstrUserBundle:UserJournalRole:' . $tpl, array(
                    'entities' => $entities
        ));
    }

    public function myJournalsAction()
    {
        $user_id = $this->getUser()->getId();

        return $this->showJournalsOfUserAction($user_id, 'show_my_journals.html.twig');
    }

    /**
     * Displays a form to edit an existing UserJournalRole entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrUserBundle:UserJournalRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserJournalRole entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrUserBundle:UserJournalRole:edit.html.twig', array(
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
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrUserBundle:UserJournalRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserJournalRole entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ujr_edit', array('id' => $id)));
        }

        return $this->render('OjstrUserBundle:UserJournalRole:edit.html.twig', array(
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
        $entity = $em->getRepository('OjstrUserBundle:UserJournalRole')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserJournalRole entity.');
        }
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('ujr'));
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
                        ->getForm()
        ;
    }

}
