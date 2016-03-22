<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Ojs\AdminBundle\Form\Type\AdminJournalApplicationType;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\CoreBundle\Params\JournalStatuses;
use Ojs\CoreBundle\Params\PublisherStatuses;
use Ojs\JournalBundle\Entity\Journal;
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
        if (!$this->isGranted('VIEW', new Journal())) {
            throw new AccessDeniedException("You not authorized for list journals!");
        }
        $cache = $this->get('array_cache');
        $source = new Entity('OjsJournalBundle:Journal');
        $source->manipulateRow(
            function (Row $row) use ($request, $cache)
            {
                /* @var Journal $entity */
                $entity = $row->getEntity();
                $entity->setDefaultLocale($request->getDefaultLocale());
                if (!is_null($entity)) {
                    if($cache->contains('grid_row_id_'.$entity->getId())){
                        $row->setClass('hidden');
                    }else{
                        $cache->save('grid_row_id_'.$entity->getId(), true);
                        $row->setField('translations.title', $entity->getTitleTranslations());
                    }
                }

                return $row;
            }
        );
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
        $rowAction[] = $gridAction->editAction('ojs_admin_application_journal_edit', 'id');
        $rowAction[] = $gridAction->showAction('ojs_admin_application_journal_show', 'id');
        $actionColumn = new ActionsColumn("actions", 'actions');
        $actionColumn->setRowActions($rowAction);

        $grid->addColumn($actionColumn);
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsAdminBundle:AdminApplication:journal.html.twig', $data);
    }

    public function detailAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Journal')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $data = [];
        $data['entity'] = $entity;
        $csrf = $this->get('security.csrf.token_manager');
        $data['token'] = $csrf->getToken('ojs_admin_application' . $id);

        return $this->render('OjsAdminBundle:AdminApplication:journal_detail.html.twig', $data);
    }

    public function editAction($id)
    {
        $entity = $this->getDoctrine()->getRepository('OjsJournalBundle:Journal')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $form = $this->createEditForm($entity);

        return $this->render('OjsAdminBundle:AdminApplication:journal_edit.html.twig', [
            'form' => $form->createView(),
            'entity' => $entity
        ]);
    }

    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->getDoctrine()->getRepository('OjsJournalBundle:Journal')->find($id);
        $this->throw404IfNotFound($entity);

        $form = $this->createEditForm($entity);
        $form->handleRequest($request);

        $entity->getCurrentTranslation()->setLocale($entity->getMandatoryLang()->getCode());
        if ($form->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect($this->generateUrl('ojs_admin_application_journal_edit', ['id'=> $id]));
        }

        return $this->render('OjsAdminBundle:AdminApplication:journal_edit.html.twig', ['entity' => $entity, 'form' => $form->createView()]);
    }

    /**
     * @param Journal $entity
     * @return $this|\Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    private function createEditForm(Journal $entity)
    {
        $form = $this->createForm(
            new AdminJournalApplicationType(), $entity,
            ['action' => $this->generateUrl('ojs_admin_application_journal_update', [
                'id' => $entity->getId()
            ])]
        );
        $form->add('submit', 'submit', [
            'label' => 'Update'
            ]
        );

        return $form;
    }

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->getDoctrine()->getRepository('OjsJournalBundle:Journal')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_application' . $id);

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $this->get('ojs_core.delete.service')->check($entity);
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->get('router')->generate('ojs_admin_application_journal_index'));
    }

    public function saveAction($id)
    {
        /** @var Journal $entity */
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Journal')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $entity->getPublisher()->setStatus(PublisherStatuses::STATUS_COMPLETE);
        $entity->setStatus(JournalStatuses::STATUS_PREPARING);
        $em->persist($entity);
        $em->flush();

        return $this->redirectToRoute('ojs_admin_journal_index');
    }

    public function rejectAction($id)
    {
        /** @var Journal $entity */
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Journal')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $entity->setStatus(JournalStatuses::STATUS_REJECTED);
        $em->persist($entity);
        $em->flush();

        $this->successFlashBag('successfully.rejected.journal');
        return $this->redirectToRoute('ojs_admin_journal_index');
    }
}
