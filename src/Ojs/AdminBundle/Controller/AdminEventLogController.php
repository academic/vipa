<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\Common\Params\EventLogParams;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * EventLog controller.
 *
 */
class AdminEventLogController extends Controller
{
    /**
     * Lists all EventLogs
     *
     * @return mixed
     */
    public function indexAction()
    {
        $logTypes = EventLogParams::adminLevelEventLogs();

        $source = new Entity('OjsUserBundle:EventLog');
        $tableAlias = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $qb) use ($tableAlias, $logTypes) {
                $expression = $qb->expr()->in($tableAlias.'.eventInfo', ':logTypes');
                $qb->andWhere($expression)->setParameters(['logTypes' => $logTypes]);
                return $qb;
            }
        );

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');
        $actionColumn = new ActionsColumn("actions", "actions");
        $actionColumn->setRowActions([$gridAction->showAction('ojs_admin_event_log_show', 'id')]);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('OjsAdminBundle:AdminEventLog:index.html.twig', ['grid' => $grid]);
    }

    /**
     * Finds and displays a EventLog entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:EventLog')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('notFound');

        return $this->render('OjsAdminBundle:AdminEventLog:show.html.twig', ['entity' => $entity]);
    }

    /**
     * Removes all EventLog records.
     *
     * @return RedirectResponse
     */
    public function flushAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsUserBundle:EventLog')->findAll();

        foreach ($entities as $entity)
            $em->remove($entity);

        $em->flush();

        $message = $this->get('translator')->trans('All records removed successfully!');
        $this->get('session')->getFlashBag()->add('success', $message);
        return $this->redirect($this->generateUrl('ojs_admin_event_log_index'));
    }
}
