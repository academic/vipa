<?php

namespace Ojs\UserBundle\Controller;

use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * User controller.
 *
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsUserBundle:User')->findAll();

        return $this->render('OjsUserBundle:User:admin/index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new User entity.
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($entity);
            $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $entity->setPassword($password);
            $entity->setAvatar($request->get('user_avatar'));
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsUserBundle:User:admin/new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a User entity.
     * @param  User                         $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     */
    public function newAction()
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsUserBundle:User:admin/new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a User entity.
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:User')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }

        return $this->render('OjsUserBundle:User:admin/show.html.twig', array(
                    'entity' => $entity));
    }

    public function profileAction($username = false)
    {
        $userRepo = $this->getDoctrine()->getRepository('OjsUserBundle:User');
        $sessionUser = $this->getUser();
        /** @var User $user */
        $user = $username ?
                $userRepo->findOneByUsername($username) :
                $sessionUser;
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:User')->find($user->getId());
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }

        $check = $this->getDoctrine()->getRepository('OjsUserBundle:Proxy')->findBy(
                array('proxyUserId' => $user->getId(), 'clientUserId' => $sessionUser->getId())
        );

        return $this->render('OjsUserBundle:User:profile.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => array(),
                    'me' => ($sessionUser == $user),
                    'isProxy' => (bool) $check));
    }

    /**
     * Displays a form to edit an existing User entity.
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:User')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        $editForm = $this->createEditForm($entity);

        return $this->render('OjsUserBundle:User:admin/edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a User entity.
     * @param  User                         $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));
        return $form;
    }

    /**
     * Edits an existing User entity.
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:User')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($entity);
            $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $entity->setPassword($password);
            $em->flush();

            return $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
        }

        return $this->render('OjsUserBundle:User:admin/edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:User')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        $entity->setStatus(-1);
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('user'));
    }

    public function blockAction(Request $request, $id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->find('OjsUserBundle:User', $id);
        if (!$user)
            throw new NotFoundResourceException("User not found.");
        $user->setIsActive(false);
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('user'));
    }

    public function unblockAction(Request $request, $id)
    {

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->find('OjsUserBundle:User', $id);
        if (!$user)
            throw new NotFoundResourceException("User not found.");
        $user->setIsActive(true);
        $em->persist($user);
        $em->flush();
        return $this->redirect($this->generateUrl('user'));
    }

    public function registerAsAuthorAction(Request $request, $journalId = null)
    {
        $userId = $this->getUser()->getId();
        $doc = $this->getDoctrine();
        $em = $doc->getManager();
        // a  journal id passed so register session user as author to this journal
        if ($journalId) {
            $user = $doc->getRepository('OjsUserBundle:User')->find($userId);
            $journal = $doc->getRepository('OjsJournalBundle:Journal')->find($journalId);
            $role = $doc->getRepository('OjsUserBundle:Role')->findOneBy(array('role' => 'ROLE_AUTHOR'));
            // check that we have already have the link
            $ujr = $doc->getRepository('OjsUserBundle:UserJournalRole')->findOneBy(array(
                'userId' => $user->getId(),
                'journalId' => $journal->getId(),
                'roleId' => $role->getId()
            ));
            $ujr = !$ujr ? new \Ojs\UserBundle\Entity\UserJournalRole() : $ujr;
            $ujr->setUser($user);
            $ujr->setJournal($journal);
            $ujr->setRole($role);
            $em->persist($ujr);
            $em->flush();

            return $this->redirect($this->generateUrl('user_join_journal'));
        }
        $myJournals = $doc->getRepository('OjsUserBundle:UserJournalRole')
                ->userJournalsWithRoles($userId, true); // only ids
        $entities = array();
        $journals = $this->getDoctrine()->getRepository('OjsJournalBundle:Journal')->findAll();
        foreach ($journals as $journal) {
            $jid = $journal->getId();
            $roles = isset($myJournals[$jid]) ? $myJournals[$jid]['roles'] : null;
            $entities[] = array('journal' => $journal, 'roles' => $roles);
        }

        return $this->render('OjsUserBundle:User:registerAuthor.html.twig', array(
                    'entities' => $entities
        ));
    }

    /**
     * Creates a form to delete a User entity by id.
     * @param  mixed $id The entity id
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        $t = $this->get('translator');

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array(
                'label' => $t->trans('Delete User Record'),
                'attr' => array(
                    'class' => 'button alert',
                    'onclick' => 'return confirm("' . $t->trans('Are you sure?') . '"); ')
            ))
            ->getForm();
    }

}
