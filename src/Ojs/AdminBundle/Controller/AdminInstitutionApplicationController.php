<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Ojs\AdminBundle\Form\Type\InstitutionApplicationType;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\Common\Params\CommonParams;
use Ojs\JournalBundle\Entity\Institution;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * Institution controller.
 *
 */
class AdminInstitutionApplicationController extends Controller
{
    public function indexAction()
    {
        $data = array();
        $source = new Entity('OjsJournalBundle:Institution', 'application');
        $tableAlias = $source->getTableAlias();

        $source->manipulateQuery(
            function (QueryBuilder $query) use ($tableAlias) {
                $query
                    ->andWhere($tableAlias . ".status = :status")
                    ->setParameter('status', 0);
                return $query;
            }
        );

        $source->addHint(Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');
        $grid->getColumn('status')->manipulateRenderCell(
            function ($value) {
                return $this->get('translator')->trans(
                    CommonParams::institutionStatus($value)
                );
            }
        );

        $rowAction = array();
        $saveAction = new RowAction('<i class="fa fa-save"></i>', 'ojs_admin_application_institution_save');
        $saveAction->setRouteParameters(['id']);
        $saveAction->setAttributes([
            'class' => 'btn btn-primary btn-xs',
            'title' => $this->get('translator')->trans('institute.merge_as_new_institute')
        ]);

        $rowAction[] = $saveAction;
        $rowAction[] = $gridAction->showAction('ojs_admin_application_institution_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_application_institution_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_application_institution_delete', 'id');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $actionColumn->setRowActions($rowAction);

        $grid->addColumn($actionColumn);
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsAdminBundle:AdminApplication:institution.html.twig', $data);
    }

    public function detailAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em
            ->getRepository('OjsJournalBundle:Institution')
            ->findOneBy(['status' => 0, 'id' => $id]);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        return $this->render('OjsAdminBundle:AdminApplication:institution_detail.html.twig', ['entity' => $entity]);
    }

    public function editAction($id)
    {
        /** @var Institution $entity */
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $action = $this->generateUrl('ojs_admin_application_institution_update', ['id' => $entity->getId()]);
        $form = $this->createForm(new InstitutionApplicationType(), $entity, ['action' => $action])->add('update', 'submit');

        return $this->render(
            'OjsAdminBundle:AdminApplication:institution_edit.html.twig',
            ['form' => $form->createView(), 'entity' => $entity]
        );
    }

    public function updateAction(Request $request, $id)
    {
        /** @var Institution $entity */
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($id);
        $this->throw404IfNotFound($entity);

        $form = $this->createForm(new InstitutionApplicationType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');
            return $this->redirect($this->generateUrl('ojs_admin_application_institution_index'));
        }

        return $this->render(
            'OjsAdminBundle:AdminApplication:institution_edit.html.twig',
            ['form' => $form->createView(), 'entity' => $entity]
        );
    }

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Institution $entity */
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($id);

        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_application' . $id);
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->get('router')->generate('ojs_admin_application_institution_index'));
    }

    public function saveAction($id)
    {
        /** @var Institution $entity */
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $entity->setStatus(1);
        $em->persist($entity);
        $em->flush();

        return $this->redirectToRoute('ojs_admin_application_institution_edit', ['id' => $entity->getId()]);
    }

    public function rejectAction($id)
    {
        /** @var Institution $entity */
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $entity->setStatus(-1);
        $em->persist($entity);
        $em->flush();

        return $this->redirectToRoute('ojs_admin_application_institution_edit', ['id' => $entity->getId()]);
    }
}
