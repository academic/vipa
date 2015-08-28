<?php

namespace Ojs\JournalBundle\Controller;

use Doctrine\ORM\Query;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Entity\InstitutionTheme;
use Ojs\AdminBundle\Form\Type\InstitutionThemeType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Doctrine\ORM\QueryBuilder;


/**
 * InstitutionThemes controller.
 *
 */
class ManagerInstitutionThemeController extends Controller
{
    /**
     * Lists all InstitutionThemes entities.
     *
     * @param $institutionId
     * @return Response
     */
    public function indexAction($institutionId)
    {
        $em = $this->getDoctrine()->getManager();
        $institution = $em->getRepository('OjsJournalBundle:Institution')->find($institutionId);
        $this->throw404IfNotFound($institution);
        if (!$this->isGrantedForInstitution($institution)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $source = new Entity('OjsJournalBundle:InstitutionTheme');
        $alias = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $qb) use ($institution, $alias) {
                $qb->andWhere($alias . '.institution = :institution')
                    ->setParameter('institution', $institution);
            }
        );
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('ojs_institution_manager_theme_show', ['institutionId' => $institution->getId(), 'id']);
        $rowAction[] = $gridAction->editAction('ojs_institution_manager_theme_edit', ['institutionId' => $institution->getId(), 'id']);
        $rowAction[] = $gridAction->deleteAction('ojs_institution_manager_theme_delete', ['institutionId' => $institution->getId(), 'id']);

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;
        $data['institution'] = $institution;

        return $grid->getGridResponse('OjsJournalBundle:ManagerInstitutionTheme:index.html.twig', $data);
    }

    /**
     * Creates a new InstitutionTheme entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction($institutionId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $institution = $em->getRepository('OjsJournalBundle:Institution')->find($institutionId);
        $this->throw404IfNotFound($institution);
        if (!$this->isGrantedForInstitution($institution)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new InstitutionTheme();
        $form = $this->createCreateForm($entity, $institution);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setInstitution($institution);
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('ojs_institution_manager_theme_show', [
                'institutionId'=> $institution->getId(),'id' => $entity->getId()
                ]
            );
        }

        return $this->render(
            'OjsJournalBundle:ManagerInstitutionTheme:new.html.twig',
            array(
                'entity' => $entity,
                'institution' => $institution,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a InstitutionTypes entity.
     *
     * @param InstitutionTheme $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(InstitutionTheme $entity, $institution)
    {
        $form = $this->createForm(
            new InstitutionThemeType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_institution_manager_theme_create',['institutionId' => $institution->getId()]),
                'method' => 'POST',
            )
        );
        $form->remove('institution');
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new InstitutionTheme entity.
     *
     * @param $institutionId
     * @return Response
     */
    public function newAction($institutionId)
    {
        $em = $this->getDoctrine()->getManager();
        $institution = $em->getRepository('OjsJournalBundle:Institution')->find($institutionId);
        $this->throw404IfNotFound($institution);
        if (!$this->isGrantedForInstitution($institution)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new InstitutionTheme();
        $form = $this->createCreateForm($entity, $institution);

        return $this->render(
            'OjsJournalBundle:ManagerInstitutionTheme:new.html.twig',
            array(
                'entity' => $entity,
                'institution' => $institution,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a InstitutionTheme entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($institutionId, InstitutionTheme $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $institution = $em->getRepository('OjsJournalBundle:Institution')->find($institutionId);
        $this->throw404IfNotFound($institution);
        if (!$this->isGrantedForInstitution($institution)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_institution_theme'.$entity->getId());

        return $this->render(
            'OjsJournalBundle:ManagerInstitutionTheme:show.html.twig',
            [
                'entity' => $entity,
                'institution' => $institution,
                'token' => $token
            ]
        );
    }

    /**
     * Displays a form to edit an existing InstitutionTheme entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($institutionId, InstitutionTheme $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $institution = $em->getRepository('OjsJournalBundle:Institution')->find($institutionId);
        $this->throw404IfNotFound($institution);
        if (!$this->isGrantedForInstitution($institution)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity, $institution);

        return $this->render(
            'OjsJournalBundle:ManagerInstitutionTheme:edit.html.twig',
            array(
                'entity' => $entity,
                'institution' => $institution,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a InstitutionTypes entity.
     *
     * @param InstitutionTheme $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(InstitutionTheme $entity, Institution $institution)
    {
        $form = $this->createForm(
            new InstitutionThemeType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_institution_manager_theme_update', array('institutionId'=> $institution->getId(),'id' => $entity->getId())),
                'method' => 'PUT',
            )
        );
        $form->remove('institution');
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing InstitutionThemes entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request,$institutionId, InstitutionTheme $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $institution = $em->getRepository('OjsJournalBundle:Institution')->find($institutionId);
        $this->throw404IfNotFound($institution);
        if (!$this->isGrantedForInstitution($institution)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity, $institution);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $entity->setInstitution($institution);
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('ojs_institution_manager_theme_edit', ['institutionId'=> $institutionId, 'id' => $entity->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:ManagerInstitutionTheme:edit.html.twig',
            array(
                'entity' => $entity,
                'institution' => $institution,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request                                            $request
     * @param  InstitutionTheme                                   $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws TokenNotFoundException
     */
    public function deleteAction(Request $request, InstitutionTheme $entity)
    {
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_institution_theme'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_admin_institution_theme_index');
    }
}
