<?php

namespace Vipa\JournalBundle\Controller;

use Doctrine\ORM\Query;
use Vipa\AdminBundle\Form\Type\PublisherType;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\JournalBundle\Entity\Publisher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Publisher controller.
 *
 */
class ManagerPublisherController extends Controller
{
    /**
     * Displays a form to edit an existing Publisher entity.
     *
     * @param $publisherId
     * @return Response
     */
    public function editAction($publisherId)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('VipaJournalBundle:Publisher')->find($publisherId);
        $this->throw404IfNotFound($entity);
        if (!$this->isGrantedForPublisher($entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'VipaJournalBundle:ManagerPublisher:edit.html.twig',
            array(
                'entity' => $entity,
                'form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Publisher entity.
     *
     * @param Publisher $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Publisher $entity)
    {
        $form = $this->createForm(
            new PublisherType(),
            $entity,
            array(
                'action' => $this->generateUrl('vipa_publisher_manager_update', array('publisherId' => $entity->getId())),
                'method' => 'PUT'
            )
        );

        return $form;
    }

    /**
     * Edits an existing Publisher entity.
     *
     * @param  Request                   $request
     * @param $publisherId
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $publisherId)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('VipaJournalBundle:Publisher')->find($publisherId);
        $this->throw404IfNotFound($entity);
        if (!$this->isGrantedForPublisher($entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('vipa_user_index');
        }

        return $this->render(
            'VipaJournalBundle:ManagerPublisher:edit.html.twig',
            array(
                'entity' => $entity,
                'form' => $editForm->createView(),
            )
        );
    }
}
