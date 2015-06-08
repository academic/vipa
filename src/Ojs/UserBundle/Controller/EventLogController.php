<?php

namespace Ojs\UserBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use Ojs\Common\Helper\ActionHelper;
use Doctrine\ORM\QueryBuilder;
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
     *
     * @todo \Ojs\Common\Params\EventLogParams role level logs classes can expand for every role
     *
     * @return mixed
     */
    public function indexAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        //get eventLog parameters according to user role
        if ($user->isAdmin()) {
            $logTypes = EventLogParams::adminLevelEventLogs();
        } else {
            //if unlisted user_role.
            $logTypes = EventLogParams::editorLevelEventLogs();
        }
        $source = new Entity('OjsUserBundle:EventLog');
        $ta = $source->getTableAlias();
        $source->manipulateQuery(function (QueryBuilder $qb) use ($ta, $logTypes, $user) {
                $qb->andWhere(
                        $qb->expr()->in($ta.'.eventInfo', ':logTypes')
                )
                ->setParameters([
                    'logTypes' => $logTypes,
                    'userId'=> $user->getId()
                ]);
            if(!$user->isAdmin()){
                $qb->andWhere("$ta.userId = :userId OR $ta.affectedUserId = :userId");
            }
            return $qb;
        });
        $grid = $this->get('grid')->setSource($source);
        $actionColumn = new ActionsColumn("actions", "actions");
        $rowAction = [];
        ActionHelper::setup($this->get('security.csrf.token_manager'), $this->get('translator'));
        $rowAction[] = ActionHelper::showAction('user_eventlog_show', 'id');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;
        return $grid->getGridResponse('OjsUserBundle:EventLog:index.html.twig', $data);
    }

    /**
     * Finds and displays a EventLog entity.
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
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        //if event isn't consider user throw 403
        if ($entity->getUserId() !== $user->getId() && $entity->getAffectedUserId() !== $user->getId() && !$user->isAdmin()) {
            throw $this->createNotFoundException('You have not permission to see this activity.');
        }

        if ($user->isAdmin()) {
            $tpl = 'OjsUserBundle:EventLog:admin/show.html.twig';
        } else {
            $tpl = 'OjsUserBundle:EventLog:show.html.twig';
        }

        return $this->render($tpl, array(
            'entity' => $entity, ));
    }

    /**
     * Removes all EventLog records.
     * Function only open for admin users.
     *
     * @return RedirectResponse
     */
    public function flushAction()
    {

        /**
         * All entities delete. Function not truncate table only removes all entry, not resets FOREIGN_KEY.
         *
         * Later this event can be log another super user log table.
         *
         * For Truncating you can use this(http://stackoverflow.com/a/9710383/2438520) link
         */
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsUserBundle:EventLog')->findAll();

        foreach ($entities as $entity) {
            $em->remove($entity);
        }
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('All records removed successfully!'));

        return $this->redirect($this->generateUrl('eventlog'));
    }
}
