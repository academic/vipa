<?php

namespace Vipa\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Vipa\AdminBundle\Form\Type\AdminJournalApplicationType;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\CoreBundle\Params\JournalStatuses;
use Vipa\CoreBundle\Params\PublisherStatuses;
use Vipa\JournalBundle\Entity\Journal;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Publisher controller.
 *
 */
class AdminJournalApplicationController extends Controller
{
    public function indexAction(Request $request)
    {
        $source = new Entity('VipaJournalBundle:Journal');
        $alias = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $query) use ($alias) {
                $query
                    ->andWhere($alias . '.status = :status')
                    ->setParameter('status', JournalStatuses::STATUS_APPLICATION);
                return $query;
            }
        );

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');


        $rowAction = array();
        $rowAction[] = $gridAction->editAction('vipa_admin_application_journal_edit', 'id');
        $rowAction[] = $gridAction->showAction('vipa_admin_application_journal_show', 'id');
        $rowAction[] = $gridAction->contactsAction('vipa_journal_journal_contact_index');
        $actionColumn = new ActionsColumn("actions", 'actions');
        $actionColumn->setRowActions($rowAction);

        $grid->addColumn($actionColumn);
        $data['grid'] = $grid;

        return $grid->getGridResponse('VipaAdminBundle:AdminApplication:journal.html.twig', $data);
    }

    public function detailAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('VipaJournalBundle:Journal')->find($id);

        $this->throw404IfNotFound($entity);
        return $this->render('VipaAdminBundle:AdminApplication:journal_detail.html.twig', [
            'entity' => $entity,
        ]);
    }

    public function editAction($id)
    {
        $entity = $this->getDoctrine()->getRepository('VipaJournalBundle:Journal')->find($id);
        $form = $this->createEditForm($entity);

        return $this->render('VipaAdminBundle:AdminApplication:journal_edit.html.twig', [
            'form' => $form->createView(),
            'entity' => $entity
        ]);
    }

    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->getDoctrine()->getRepository('VipaJournalBundle:Journal')->find($id);
        $this->throw404IfNotFound($entity);

        $form = $this->createEditForm($entity);
        $form->handleRequest($request);

        $entity->getCurrentTranslation()->setLocale($entity->getMandatoryLang()->getCode());
        if ($form->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('vipa_admin_application_journal_edit', ['id'=> $id]);
        }

        return $this->render('VipaAdminBundle:AdminApplication:journal_edit.html.twig', ['entity' => $entity, 'form' => $form->createView()]);
    }

    /**
     * @param Journal $entity
     * @return $this|\Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    private function createEditForm(Journal $entity)
    {
        $form = $this->createForm(
            new AdminJournalApplicationType(), $entity,
            ['action' => $this->generateUrl('vipa_admin_application_journal_update', [
                'id' => $entity->getId()
            ])]
        );
        $form->add('submit', 'submit', [
            'label' => 'Update'
            ]
        );

        return $form;
    }

    public function saveAction($id)
    {
        /** @var Journal $entity */
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('VipaJournalBundle:Journal')->find($id);

        $this->throw404IfNotFound($entity);

        $entity->getPublisher()->setStatus(PublisherStatuses::STATUS_COMPLETE);
        $entity->setStatus(JournalStatuses::STATUS_PREPARING);
        $em->persist($entity);
        $em->flush();

        return $this->redirectToRoute('vipa_admin_application_journal_index');
    }

    public function rejectAction($id)
    {
        /** @var Journal $entity */
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('VipaJournalBundle:Journal')->find($id);

        $this->throw404IfNotFound($entity);

        $entity->setStatus(JournalStatuses::STATUS_REJECTED);
        $em->persist($entity);
        $em->flush();

        $this->successFlashBag('successfully.rejected.journal');
        return $this->redirectToRoute('vipa_admin_application_journal_index');
    }
}
