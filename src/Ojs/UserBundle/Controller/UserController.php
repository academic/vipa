<?php

namespace Ojs\UserBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Helper\ActionHelper;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\UserBundle\Entity\Role;
use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Entity\UserJournalRole;
use Ojs\UserBundle\Entity\UserRepository;
use Ojs\UserBundle\Form\UserType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\Yaml;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * User controller.
 *
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     * @return Response
     */
    public function indexAction()
    {
        if(!$this->isGranted('VIEW', new User())) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsUserBundle:User')->findAll();
        $source = new Entity("OjsUserBundle:User");
        $grid = $this->get('grid');
        $grid->setSource($source);
        ActionHelper::setup($this->get('security.csrf.token_manager'));

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = ActionHelper::switchUserAction('ojs_public_index', ['username'], 'ROLE_SUPER_ADMIN');
        $rowAction[] = ActionHelper::showAction('user_show', 'id');
        $rowAction[] = ActionHelper::editAction('user_edit', 'id');
        $rowAction[] = ActionHelper::userBanAction();
        $rowAction[] = ActionHelper::deleteAction('user_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('OjsUserBundle:User:admin/index.html.twig', ['grid' => $grid, 'entities' => $entities]);
    }

    /**
     * Creates a new User entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        if(!$this->isGranted('CREATE', new User())) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
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

            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('user_show', [
                'id' => $entity->getId(),
                ]
            );
        }

        return $this->render('OjsUserBundle:User:admin/new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a User entity.
     * @param  User $entity The entity
     * @return Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST',
            'helper' => $this->get('okulbilisim_location.form.helper'),
        ));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @return Response
     */
    public function newAction()
    {
        if(!$this->isGranted('CREATE', new User())) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        $entity = new User();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsUserBundle:User:admin/new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:User')->find($id);
        if(!$this->isGranted('CREATE', $entity)) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }

        return $this->render('OjsUserBundle:User:admin/show.html.twig', array(
                    'entity' => $entity, ));
    }

    /**
     * @param  bool     $username
     * @return Response
     */
    public function profileAction($username = false)
    {
        /** @var UserRepository $userRepo */
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
                    'isProxy' => (bool) $check, ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:User')->find($id);
        if(!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
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
            'helper' => $this->get('okulbilisim_location.form.helper'),
        ));

        return $form;
    }

    /**
     * Edits an existing User entity.
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var User $entity */
        $entity = $em->getRepository('OjsUserBundle:User')->find($id);
        if(!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        $oldPassword = $entity->getPassword();
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        /** @var DocumentManager $dm */
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        if ($editForm->isValid()) {
            $password = $entity->getPassword();
            if (empty($password)) {
                $entity->setPassword($oldPassword);
            } else {
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($entity);
                $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
                $entity->setPassword($password);
            }

            $avatar = $request->request->get('avatar');
            $ir = $dm->getRepository('OjsSiteBundle:ImageOptions');
            $imageOptions = $ir->init($avatar, $entity, 'avatar');
            $dm->persist($imageOptions);
            $dm->flush();

            $em->flush();

            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('user_edit', ['id' => $id]);
        }

        return $this->render('OjsUserBundle:User:admin/edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var User $entity */
        $entity = $em->getRepository('OjsUserBundle:User')->find($id);
        if(!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('notFound'));
        }

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('user'.$id);
        if($token!=$request->get('_token'))
            throw new TokenNotFoundException("Token Not Found!");

        $entity->setStatus(-1);
        $em->remove($entity);
        $em->flush();

        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('user');
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function blockAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->find('OjsUserBundle:User', $id);
        if(!$this->isGranted('EDIT', $user)) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        if (!$user) {
            throw new NotFoundResourceException("User not found.");
        }
        $user->setIsActive(false);
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('user'));
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @throws ORMException
     */
    public function unblockAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->find('OjsUserBundle:User', $id);
        if(!$this->isGranted('EDIT', $user)) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        if (!$user) {
            throw new NotFoundResourceException("User not found.");
        }
        $user->setIsActive(true);
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('user'));
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
            $ujr = $doc->getRepository('OjsUserBundle:UserJournalRole')->findOneBy(array(
                'userId' => $user->getId(),
                'journalId' => $journal->getId(),
                'roleId' => $role->getId(),
            ));
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

        return $this->render('OjsUserBundle:User:registerAuthor.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a form to delete a User entity by id.
     * @param  mixed $id The entity id
     * @return Form  The form
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
                                'onclick' => 'return confirm("'.$t->trans('Are you sure?').'"); ', ),
                        ))
                        ->getForm();
    }

    /**
     * @param  Request                   $request
     * @param  User                      $user
     * @return RedirectResponse|Response
     */
    public function sendMailAction(Request $request, User $user)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        $data = [];
        $serializer = $this->get('serializer');
        $session = $this->get('session');
        if ($request->isMethod('POST')) {
            $mailData = $request->get('mail');
            $mailer = $this->get('mailer');
            $message = $mailer->createMessage()
                    ->setFrom($this->container->getParameter('system_email'))
                    ->setTo($user->getEmail())
                    ->setSubject($mailData['subject'])
                    ->setBody($mailData['body'])
                    ->setContentType('text/html');
            $mailer->send($message);
            $session->getFlashBag()->add('success', $this->get('translator')->trans('Email sending succefully.'));
            $session->save();

            return $this->redirect($this->get('router')->generate('ujr_show_users_ofjournal', ['journal_id' => $journal->getId()]));
        }
        /** @var QueryBuilder $qb */
        $qb = $em->createQueryBuilder();
        $qb->select('t')
                ->from('OjsJournalBundle:MailTemplate', 't')
                ->where(
                        $qb->expr()->orX(
                                $qb->expr()->isNull('t.journalId'), $qb->expr()->eq('t.journalId', ':journal')
                        )
                )
                ->setParameter('journal', $journal->getId());
        $templates = $qb->getQuery()->getResult();
        $data['templates'] = $templates;
        $data['user'] = $user;
        $data['parameters'] = $request->query->all();
        array_walk($data['parameters'], function (&$val, $key) {
            $val = urldecode($val);
        });

        $data['templateVars'] = json_encode(
                array_merge(array(
            'journal' => json_decode($serializer->serialize($journal, 'json')),
            'user' => json_decode($serializer->serialize($this->getUser(), 'json')),
                        ), $data['parameters'])
        );

        $yamlParser = new Yaml\Parser();
        $defaultTemplates = $yamlParser->parse(file_get_contents(
                        $this->container->getParameter('kernel.root_dir').
                        '/../src/Ojs/JournalBundle/Resources/data/mailtemplates.yml'
        ));
        $tplKey = $request->get('template');
        $data['selectedTemplate'] = $tplKey ? (isset($defaultTemplates[$tplKey]) ? json_encode($defaultTemplates[$tplKey]) : null) : null;

        return $this->render('OjsUserBundle:UserJournalRole:send_mail.html.twig', $data);
    }
}
