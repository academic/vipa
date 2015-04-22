<?php

namespace Ojs\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        $superAdmin = $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        $author = $this->container->get('security.context')->isGranted('ROLE_AUTHOR');
        $editor = $this->container->get('security.context')->isGranted('ROLE_EDITOR');

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

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $entities = $qb
            ->select('e')
            ->from('OjsUserBundle:EventLog', 'e')
            ->where($qb->expr()->in('e.eventInfo', ':logTypes'))
            ->setParameter('logTypes', $logTypes);

        //admin can see every user log. But other users can see only own considered logs
        if (!$superAdmin) {
            $entities = $entities
                ->andWhere('e.userId = :userId OR e.affectedUserId = :userId')
                ->setParameter('userId', $userId);
        }
        $entities = $entities
            ->getQuery()
            ->getResult();

        return $this->render('OjsUserBundle:EventLog:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a EventLog entity.
     *
     */
    public function showAction($id)
    {
        $superAdmin = $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:EventLog')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        $user = $this->getUser();
        $userId = $user->getId();

        //if event isn't consider user throw 403
        if($entity->getUserId() !== $userId && $entity->getAffectedUserId() !== $userId && !$superAdmin){
            throw $this->createNotFoundException('You have not permission to see this activity.');
        }

        if ($superAdmin) {
            $tpl = 'OjsUserBundle:EventLog:admin/show.html.twig';
        } else {
            $tpl = 'OjsUserBundle:EventLog:show.html.twig';
        }

        return $this->render($tpl, array(
            'entity' => $entity));
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
