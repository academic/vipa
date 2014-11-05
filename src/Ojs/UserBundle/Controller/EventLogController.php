<?php

namespace Ojs\UserBundle\Controller;

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

}
