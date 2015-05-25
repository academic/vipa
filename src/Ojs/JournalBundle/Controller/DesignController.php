<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Helper\ActionHelper;
use Ojs\JournalBundle\Entity\Design;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Form\DesignType;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * Design controller.
 *
 */
class DesignController extends Controller
{
    /**
     * Lists all Design entities.
     *
     */
    public function indexAction()
    {
        $source = new Entity('OjsJournalBundle:Design');
        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        ActionHelper::setup($this->get('security.csrf.token_manager'));

        $rowAction[] = ActionHelper::showAction('design_show', 'id');
        $rowAction[] = ActionHelper::editAction('design_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('design_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:Design:index.html.twig', $data);
    }

    /**
     * Creates a new Design entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Design();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('design_show', ['id' => $entity->getId()]);
        }

        return $this->render('OjsJournalBundle:Design:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Design entity.
     *
     * @param Design $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Design $entity)
    {
        $form = $this->createForm(new DesignType(), $entity, array(
            'action' => $this->generateUrl('design_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Design entity.
     *
     */
    public function newAction()
    {
        $entity = new Design();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:Design:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Design entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Design')->find($id);
        $this->throw404IfNotFound($entity);

        return $this->render('OjsJournalBundle:Design:show.html.twig', array(
                    'entity' => $entity,
            )
        );
    }

    /**
     * Displays a form to edit an existing Design entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Design')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:Design:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Design entity.
     *
     * @param Design $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Design $entity)
    {
        $form = $this->createForm(new DesignType(), $entity, array(
            'action' => $this->generateUrl('design_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Design entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Design')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('design_edit', ['id' => $id]);
        }

        return $this->render('OjsJournalBundle:Design:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param Design $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws TokenNotFoundException
     */
    public function deleteAction(Request $request, Design $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('design'.$entity->getId());
        if($token!=$request->get('_token'))
            throw new TokenNotFoundException("Token Not Found!");
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('design');
    }
}
