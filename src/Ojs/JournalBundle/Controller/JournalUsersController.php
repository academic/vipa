<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Form\Type\JournalUserType;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ojs\UserBundle\Entity\UserJournalRole;

/**
 * JournalUsers controller.
 *
 */
class JournalUsersController extends Controller
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
        $source = new Entity('OjsUserBundle:UserJournalRole');
        $ta = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $qb) use ($journal, $ta) {
                $qb->andWhere($ta . '.journal = :journal')
                    ->setParameter('journal', $journal);
            }
        );
        $grid = $this->get('grid');
        $gridAction = $this->get('grid_action');
        $grid->setSource($source);
        $actionColumn = new ActionsColumn("actions", "actions");
        $rowAction = [];

        $rowAction[] = $gridAction->showAction('ujr_show', 'id');
        $rowAction[] = $gridAction->editAction('ujr_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ujr_delete', 'id');
        $rowAction[] = $gridAction->sendMailAction('user_send_mail');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $grid->showColumns(['journal.title']);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse(
            'OjsUserBundle:UserJournalRole:index.html.twig',
            [
                'grid' => $grid
            ]
        );
    }

    public function newUserAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new User();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsJournalBundle:JournalUsers:new_user.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a new User entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createUserAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'userRole')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new User();
        $form = $this->createCreateForm($entity);
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

            //add user journal roles
            if(count($formData->getJournalRoles())>0){
                foreach($formData->getJournalRoles() as $role){
                    $userJournalRole = new UserJournalRole();
                    $userJournalRole->setJournal($journal);
                    $userJournalRole->setRole($role);
                    $userJournalRole->setUser($entity);
                    $em->persist($userJournalRole);
                }
            }
            $em->flush();

            $this->successFlashBag('successful.create');
            return $this->redirectToRoute('journal_users');
        }

        return $this->render(
            'OjsAdminBundle:AdminUser:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a User entity.
     * @param  User $entity
     * @return Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(
            new JournalUserType(),
            $entity,
            array(
                'action' => $this->generateUrl('journal_users_create_user'),
                'method' => 'POST',
            )
        );

        return $form;
    }
}
