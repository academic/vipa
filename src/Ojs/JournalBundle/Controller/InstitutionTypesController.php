<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Helper\ActionHelper;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\InstitutionTypes;
use Ojs\JournalBundle\Form\InstitutionTypesType;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * InstitutionTypes controller.
 *
 */
class InstitutionTypesController extends Controller
{
    /**
     * Lists all InstitutionTypes entities.
     *
     */
    public function indexAction()
    {
        if(!$this->isGranted('VIEW', new InstitutionTypes())) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        $source = new Entity('OjsJournalBundle:InstitutionTypes');
        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        ActionHelper::setup($this->get('security.csrf.token_manager'));

        $rowAction[] = ActionHelper::showAction('institution_types_show', 'id');
        $rowAction[] = ActionHelper::editAction('institution_types_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('institution_types_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:InstitutionTypes:index.html.twig', $data);
    }

    /**
     * Creates a new InstitutionTypes entity.
     *
     */
    public function createAction(Request $request)
    {
        if(!$this->isGranted('CREATE', new InstitutionTypes())) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        $entity = new InstitutionTypes();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('institution_types_show', ['id' => $entity->getId()]);
        }

        return $this->render('OjsJournalBundle:InstitutionTypes:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a InstitutionTypes entity.
     *
     * @param InstitutionTypes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(InstitutionTypes $entity)
    {
        $form = $this->createForm(new InstitutionTypesType(), $entity, array(
            'action' => $this->generateUrl('institution_types_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new InstitutionTypes entity.
     *
     */
    public function newAction()
    {
        if(!$this->isGranted('CREATE', new InstitutionTypes())) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        $entity = new InstitutionTypes();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:InstitutionTypes:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a InstitutionTypes entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:InstitutionTypes')->find($id);
        if(!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);

        return $this->render('OjsJournalBundle:InstitutionTypes:show.html.twig', array(
                    'entity' => $entity,
            )
        );
    }

    /**
     * Displays a form to edit an existing InstitutionTypes entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:InstitutionTypes')->find($id);
        if(!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:InstitutionTypes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a InstitutionTypes entity.
     *
     * @param InstitutionTypes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(InstitutionTypes $entity)
    {
        $form = $this->createForm(new InstitutionTypesType(), $entity, array(
            'action' => $this->generateUrl('institution_types_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing InstitutionTypes entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:InstitutionTypes')->find($id);
        if(!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('institution_types_edit', ['id' => $id]);
        }

        return $this->render('OjsJournalBundle:InstitutionTypes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param InstitutionTypes $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws TokenNotFoundException
     */
    public function deleteAction(Request $request, InstitutionTypes $entity)
    {
        $em = $this->getDoctrine()->getManager();
        if(!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('institution_types'.$entity->getId());
        if($token!=$request->get('_token'))
            throw new TokenNotFoundException("Token Not Found!");
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('institution_types');
    }
}
