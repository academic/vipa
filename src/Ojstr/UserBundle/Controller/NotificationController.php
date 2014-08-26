<?php

namespace Ojstr\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojstr\UserBundle\Entity\Notification;
use Ojstr\UserBundle\Form\NotificationType;

/**
 * Notification controller.
 *
 */
class NotificationController extends Controller {

    /**
     * Lists all Notification entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjstrUserBundle:Notification')->findAll();

        return $this->render('OjstrUserBundle:Notification:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Notification entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrUserBundle:Notification')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notification entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrUserBundle:Notification:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Notification entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjstrUserBundle:Notification')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Notification entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_notification'));
    } 

}
