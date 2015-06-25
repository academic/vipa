<?php

namespace Ojs\UserBundle\Controller;

use Doctrine\ORM\Query;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\Common\Params\EventLogParams;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * EventLog controller.
 *
 */
class EventLogController extends Controller
{
    /**
     * Lists all EventLog according to user role.
     * @todo Add items to EventLogParams for every role
     *
     * @return mixed
     */
    public function indexAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        $logTypes = EventLogParams::editorLevelEventLogs();

        $source = new Entity('OjsUserBundle:EventLog');
        $source->addHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');
        $tableAlias = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $qb) use ($tableAlias, $logTypes, $user) {
                $expression = $qb->expr()->in($tableAlias.'.eventInfo', ':logTypes');
                $qb->andWhere($expression)->setParameter('logTypes', $logTypes);
                $qb->andWhere("$tableAlias.userId = :userId OR $tableAlias.affectedUserId = :userId")
                    ->setParameter('userId', $user->getId());
                return $qb;
            }
        );

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');
        $actionColumn = new ActionsColumn("actions", "actions");
        $rowAction[] = $gridAction->showAction('user_eventlog_show', 'id');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('OjsUserBundle:EventLog:index.html.twig', ['grid' => $grid]);
    }

    /**
     * Finds and displays an EventLog entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        /** @var User $user */
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:EventLog')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('notFound');

        // If the event about the user, throw 403
        if ($entity->getUserId() !== $user->getId() && $entity->getAffectedUserId() !== $user->getId())
            throw $this->createNotFoundException('You have not permission to see this activity.');

        return $this->render('OjsUserBundle:EventLog:show.html.twig', ['entity' => $entity]);
    }
}
