<?php

namespace Ojs\JournalBundle\Controller;

use Doctrine\ORM\Query;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\AdminBundle\Form\Type\InstitutionType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * Institution controller.
 *
 */
class ManagerInstitutionController extends Controller
{
    /**
     * Displays a form to edit an existing Institution entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($institutionId)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($institutionId);
        $this->throw404IfNotFound($entity);
        if (!$this->isGrantedForInstitution($entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsJournalBundle:ManagerInstitution:edit.html.twig',
            array(
                'entity' => $entity,
                'form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Institution entity.
     *
     * @param Institution $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Institution $entity)
    {
        $form = $this->createForm(
            new InstitutionType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_institution_manager_update', array('institutionId' => $entity->getId())),
                'method' => 'PUT',
                'institutionsEndPoint' => $this->generateUrl('api_get_institutions'),
                'institutionEndPoint' => $this->generateUrl('api_get_institution')
            )
        );

        return $form;
    }

    /**
     * Edits an existing Institution entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $institutionId)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($institutionId);
        $this->throw404IfNotFound($entity);
        if (!$this->isGrantedForInstitution($entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('ojs_user_index');
        }

        return $this->render(
            'OjsJournalBundle:ManagerInstitution:edit.html.twig',
            array(
                'entity' => $entity,
                'form' => $editForm->createView(),
            )
        );
    }
}
