<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Helper\ActionHelper;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalTheme;
use Ojs\JournalBundle\Form\JournalThemeType;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * JournalTheme controller.
 *
 */
class JournalThemeController extends Controller
{

    /**
     * Lists all JournalTheme entities.
     *
     */
    public function indexAction()
    {
        $source = new Entity('OjsJournalBundle:JournalTheme');
        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        ActionHelper::setup($this->get('security.csrf.token_manager'));

        $rowAction[] = ActionHelper::showAction('admin_journaltheme_show', 'id');
        $rowAction[] = ActionHelper::editAction('admin_journaltheme_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('admin_journaltheme_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:JournalTheme:index.html.twig', $data);
    }
    /**
     * Creates a new JournalTheme entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new JournalTheme();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('admin_journaltheme_show', ['id' => $entity->getId()]);
        }

        return $this->render('OjsJournalBundle:JournalTheme:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a JournalTheme entity.
     *
     * @param JournalTheme $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(JournalTheme $entity)
    {
        $form = $this->createForm(new JournalThemeType(), $entity, array(
            'action' => $this->generateUrl('admin_journaltheme_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new JournalTheme entity.
     *
     */
    public function newAction()
    {
        $entity = new JournalTheme();
        $form   = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:JournalTheme:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a JournalTheme entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalTheme')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        return $this->render('OjsJournalBundle:JournalTheme:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing JournalTheme entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalTheme')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:JournalTheme:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a JournalTheme entity.
     *
     * @param JournalTheme $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(JournalTheme $entity)
    {
        $form = $this->createForm(new JournalThemeType(), $entity, array(
            'action' => $this->generateUrl('admin_journaltheme_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing JournalTheme entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalTheme')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('admin_journaltheme_edit', ['id' => $id]);
        }

        return $this->render('OjsJournalBundle:JournalTheme:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param JournalTheme $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws TokenNotFoundException
     */
    public function deleteAction(Request $request, JournalTheme $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('admin_journaltheme'.$entity->getId());
        if($token!=$request->get('_token'))
            throw new TokenNotFoundException("Token Not Found!");
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('admin_journaltheme');
    }
}
