<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ODM\MongoDB\DocumentManager;
use Ojs\Common\Helper\ActionHelper;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Form\InstitutionType;

/**
 * Institution controller.
 *
 */
class InstitutionController extends Controller
{
    /**
     * Lists all Institution entities.
     *
     */
    public function indexAction()
    {
        $source = new Entity('OjsJournalBundle:Institution');
        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = ActionHelper::showAction('institution_show', 'id');
        $rowAction[] = ActionHelper::editAction('institution_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('institution_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $data = [];
        $data['grid'] = $grid;
        return $grid->getGridResponse('OjsJournalBundle:Institution:index.html.twig', $data);
    }

    /**
     * Creates a new Institution entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Institution();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $header = $request->request->get('header');
            $cover = $request->request->get('logo');
            if($header){
                $entity->setHeaderOptions(json_encode($header));
            }
            if($cover){
                $entity->setLogoOptions(json_encode($cover));
            }
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('institution_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsJournalBundle:Institution:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Institution entity.
     *
     * @param Institution $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Institution $entity)
    {
        $form = $this->createForm(new InstitutionType(), $entity, array(
            'action' => $this->generateUrl('institution_create'),
            'method' => 'POST',
            'helper' => $this->get('okulbilisim_location.form.helper')
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Institution entity.
     *
     */
    public function newAction()
    {
        $entity = new Institution();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:Institution:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Institution entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($id);
        $this->throw404IfNotFound($entity);

        return $this->render('OjsJournalBundle:Institution:show.html.twig', array(
            'entity' => $entity,));
    }

    /**
     * Displays a form to edit an existing Institution entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:Institution:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        ));
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
        $form = $this->createForm(new InstitutionType(), $entity, array(
            'action' => $this->generateUrl('institution_update', array('id' => $entity->getId())),
            'method' => 'POST',
            'helper' => $this->get('okulbilisim_location.form.helper')
        ));

        return $form;
    }

    /**
     * Edits an existing Institution entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Institution $entity */
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $header = $request->request->get('header');
            $cover = $request->request->get('logo');
            if($header){
                $entity->setHeaderOptions(json_encode($header));
            }
            if($cover){
                $entity->setLogoOptions(json_encode($cover));
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('institution_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:Institution:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Institution entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($id);
        $this->throw404IfNotFound($entity);
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('institution'));
    }

}
