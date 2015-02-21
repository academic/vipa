<?php

namespace Ojs\UserBundle\Controller;

use GuzzleHttp\Subscriber\Redirect;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojs\UserBundle\Entity\EventLog;

/**
 * EventLog controller.
 *
 */
class EventLogController extends Controller
{
    /**
     * Lists all EventLog entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjsUserBundle:EventLog')->findAll();

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
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:EventLog')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EventLog entity.');
        }

        return $this->render('OjsUserBundle:EventLog:show.html.twig', array(
                    'entity' => $entity));
    }

    /**
     * Removes all EventLog records
     *
     * @return mixed
     */
    public function flushAction(){

        /**
         * All entities delete. Function not truncate table only removes all entry, not resets FOREIGN_KEY.
         *
         * Later this event can be log another super user log table.
         *
         * For Truncating you can use this(http://stackoverflow.com/a/9710383/2438520) link
         */
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsUserBundle:EventLog')->findAll();

        foreach($entities as $entity){

            $em->remove($entity);
        }
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('All records removed successfully!'));


        return $this->redirect($this->generateUrl('eventlog'));
    }
}
