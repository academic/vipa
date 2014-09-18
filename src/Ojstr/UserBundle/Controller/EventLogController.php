<?php

namespace Ojstr\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojstr\UserBundle\Entity\EventLog;

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

        $entities = $em->getRepository('OjstrUserBundle:EventLog')->findAll();

        return $this->render('OjstrUserBundle:EventLog:index.html.twig', array(
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
        $entity = $em->getRepository('OjstrUserBundle:EventLog')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EventLog entity.');
        }

        return $this->render('OjstrUserBundle:EventLog:show.html.twig', array(
                    'entity' => $entity));
    }

}
