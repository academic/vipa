<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\AdminBundle\Form\Type\JournalApplicationFileType;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalApplicationFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * AdminJournalApplicationFileController controller.
 *
 */
class AdminJournalApplicationFileController extends Controller
{

    /**
     * Lists all Index entities.
     *
     */
    public function indexAction()
    {
        $source = new Entity('OjsJournalBundle:JournalApplicationFile');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('ojs_admin_application_file_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_application_file_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_application_file_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsAdminBundle:AdminJournalApplicationFile:index.html.twig', $data);
    }

    /**
     * Creates a new Index entity.
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new JournalApplicationFile();
        $form = $this->createCreateForm($entity)
            ->add('create', 'submit', array('label' => 'c'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('ojs_admin_application_file_show', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsAdminBundle:AdminJournalApplicationFile:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Index entity.
     *
     * @param JournalApplicationFile $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(JournalApplicationFile $entity)
    {
        $languages = [];
        if (is_array($this->container->getParameter('languages'))) {
            foreach ($this->container->getParameter('languages') as $key => $language) {
                if (array_key_exists('code', $language)) {
                    $languages[$language['code']] = $language['name'];
                }
            }
        }
        $form = $this->createForm(
            new JournalApplicationFileType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_application_file_create'),
                'method' => 'POST',
                'languages' => $languages
            )
        );

        return $form;
    }

    /**
     * Displays a form to create a new Index entity.
     *
     */
    public function newAction()
    {
        $entity = new JournalApplicationFile();
        $form = $this->createCreateForm($entity)
            ->add('create', 'submit', array('label' => 'c'));

        return $this->render(
            'OjsAdminBundle:AdminJournalApplicationFile:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Index entity.
     *
     * @param  JournalApplicationFile $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(JournalApplicationFile $entity)
    {
        $this->throw404IfNotFound($entity);
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_application_file'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminJournalApplicationFile:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing Index entity.
     *
     * @param  JournalApplicationFile $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(JournalApplicationFile $entity)
    {
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity)
            ->add('save', 'submit');

        return $this->render(
            'OjsAdminBundle:AdminJournalApplicationFile:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Index entity.
     *
     * @param JournalApplicationFile $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(JournalApplicationFile $entity)
    {
        $languages = [];
        if (is_array($this->container->getParameter('languages'))) {
            foreach ($this->container->getParameter('languages') as $key => $language) {
                if (array_key_exists('code', $language)) {
                    $languages[$language['code']] = $language['name'];
                }
            }
        }
        $form = $this->createForm(
            new JournalApplicationFileType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_application_file_update', array('id' => $entity->getId())),
                'method' => 'PUT',
                'languages' => $languages
            )
        );

        return $form;
    }

    /**
     * Edits an existing Index entity.
     *
     * @param  Request $request
     * @param  JournalApplicationFile $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, JournalApplicationFile $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity)
            ->add('save', 'submit');
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect(
                $this->generateUrl('ojs_admin_application_file_edit', array('id' => $entity->getId()))
            );
        }

        return $this->render(
            'OjsAdminBundle:AdminJournalApplicationFile:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a Index entity.
     *
     * @param  Request $request
     * @param  JournalApplicationFile $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, JournalApplicationFile $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_application_file'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $this->get('ojs_core.delete.service')->check($entity);
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_admin_application_file_index');
    }
}
