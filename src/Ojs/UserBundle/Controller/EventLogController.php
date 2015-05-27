<?php

namespace Ojs\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use Ojs\Common\Helper\ActionHelper;
use Doctrine\ORM\QueryBuilder;

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
        $user = $this->getUser();
        $userId = $user->getId();
        $superAdmin = $this->isGranted('ROLE_SUPER_ADMIN');
        $author = $this->isGranted('ROLE_AUTHOR');
        $editor = $this->isGranted('ROLE_EDITOR');

        //get eventLog parameters according to user role
        if ($superAdmin) {
            $logTypes = \Ojs\Common\Params\EventLogParams::adminLevelEventLogs();
        } elseif ($author) {
            $logTypes = \Ojs\Common\Params\EventLogParams::authorLevelEventLogs();
        } elseif ($editor) {
            $logTypes = \Ojs\Common\Params\EventLogParams::editorLevelEventLogs();
        } else {
            //if unlisted user_role.
            $logTypes = \Ojs\Common\Params\EventLogParams::editorLevelEventLogs();
        }
        $source = new Entity('OjsUserBundle:EventLog');
        $ta = $source->getTableAlias();
        $source->manipulateQuery(function (QueryBuilder $qb) use ($ta, $logTypes, $superAdmin, $userId) {
                $qb->andWhere(
                        $qb->expr()->in($ta.'.eventInfo', ':logTypes')
                )
                ->setParameters([
                    'logTypes' => $logTypes,
                    'userId'=>$userId
                ]);
            if(!$superAdmin){
                $qb->andWhere("$ta.userId = :userId OR $ta.affectedUserId = :userId");
            }
            return $qb;
        });
        $grid = $this->get('grid')->setSource($source);
        $actionColumn = new ActionsColumn("actions", "actions");
        $rowAction = [];
        ActionHelper::setup($this->get('security.csrf.token_manager'));
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
     */
    public function showAction($id)
    {
        $superAdmin = $this->isGranted('ROLE_SUPER_ADMIN');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:EventLog')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        $user = $this->getUser();
        $userId = $user->getId();

        //if event isn't consider user throw 403
        if ($entity->getUserId() !== $userId && $entity->getAffectedUserId() !== $userId && !$superAdmin) {
            throw $this->createNotFoundException('You have not permission to see this activity.');
        }

        if ($superAdmin) {
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
     * @return redirect
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
