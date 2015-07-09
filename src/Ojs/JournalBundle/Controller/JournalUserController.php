<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalUser;
use Ojs\JournalBundle\Form\Type\JournalUserType;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\Collection;
use Ojs\JournalBundle\Entity\JournalRole;
use Doctrine\ORM\Query;

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

        $rowAction = [];
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
            'OjsAdminBundle:AdminUser:new.html.twig',
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
            new JournalUserType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_journal_user_create', ['journalId' => $journalId]),
                'method' => 'POST',
            )
        );

        return $form;
    }
}
