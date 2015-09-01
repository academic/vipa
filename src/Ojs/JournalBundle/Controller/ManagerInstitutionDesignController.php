<?php

namespace Ojs\JournalBundle\Controller;

use Doctrine\ORM\Query;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\InstitutionDesign;
use Ojs\AdminBundle\Form\Type\InstitutionDesignType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Doctrine\ORM\QueryBuilder;

/**
 * InstitutionDesign controller.
 *
 */
class ManagerInstitutionDesignController extends Controller
{
    /**
     * Lists all InstitutionDesigns entities.
     *
     */
    public function indexAction($institutionId)
    {
        $em = $this->getDoctrine()->getManager();
        $institution = $em->getRepository('OjsJournalBundle:Institution')->find($institutionId);
        $this->throw404IfNotFound($institution);
        if (!$this->isGrantedForInstitution($institution)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $source = new Entity('OjsJournalBundle:InstitutionDesign');
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

        $rowAction[] = $gridAction->showAction('ojs_institution_manager_design_show', ['institutionId' => $institution->getId(), 'id']);
        $rowAction[] = $gridAction->editAction('ojs_institution_manager_design_edit', ['institutionId' => $institution->getId(), 'id']);
        $rowAction[] = $gridAction->deleteAction('ojs_institution_manager_design_delete', ['institutionId' => $institution->getId(), 'id']);

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;
        $data['institution'] = $institution;

        return $grid->getGridResponse('OjsJournalBundle:ManagerInstitutionDesign:index.html.twig', $data);
    }

    /**
     * Creates a new InstitutionDesign entity.
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
        $entity = new InstitutionDesign();
        $form = $this->createCreateForm($entity, $institution);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setInstitution($institution);
            $entity->setContent(
                $this->prepareDesignContent($entity->getEditableContent())
            );
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('ojs_institution_manager_design_show', ['institutionId'=> $institution->getId(),'id' => $entity->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:ManagerInstitutionDesign:new.html.twig',
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
     * @param InstitutionDesign $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(InstitutionDesign $entity, $institution)
    {
        $form = $this->createForm(
            new InstitutionDesignType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_institution_manager_design_create',['institutionId' => $institution->getId()]),
                'method' => 'POST',
            )
        );
        $form->remove('institution');
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new InstitutionDesign entity.
     *
     */
    public function newAction($institutionId)
    {
        $em = $this->getDoctrine()->getManager();
        $institution = $em->getRepository('OjsJournalBundle:Institution')->find($institutionId);
        $this->throw404IfNotFound($institution);
        if (!$this->isGrantedForInstitution($institution)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new InstitutionDesign();
        $form = $this->createCreateForm($entity, $institution);

        return $this->render(
            'OjsJournalBundle:ManagerInstitutionDesign:new.html.twig',
            array(
                'entity' => $entity,
                'institution' => $institution,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a InstitutionDesign entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($institutionId, InstitutionDesign $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $institution = $em->getRepository('OjsJournalBundle:Institution')->find($institutionId);
        $this->throw404IfNotFound($institution);
        if (!$this->isGrantedForInstitution($institution)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_institution_manager_design'.$entity->getId());

        return $this->render(
            'OjsJournalBundle:ManagerInstitutionDesign:show.html.twig',
            [
                'entity' => $entity,
                'institution' => $institution,
                'token' => $token
            ]
        );
    }

    /**
     * Displays a form to edit an existing InstitutionDesign entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($institutionId, InstitutionDesign $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $institution = $em->getRepository('OjsJournalBundle:Institution')->find($institutionId);
        $this->throw404IfNotFound($institution);
        if (!$this->isGrantedForInstitution($institution)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity->setEditableContent($this->prepareEditContent($entity->getEditableContent()));
        $editForm = $this->createEditForm($entity, $institution);

        return $this->render(
            'OjsJournalBundle:ManagerInstitutionDesign:edit.html.twig',
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
     * @param InstitutionDesign $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(InstitutionDesign $entity, $institution)
    {
        $form = $this->createForm(
            new InstitutionDesignType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_institution_manager_design_update', array('institutionId' => $institution->getId(),'id' => $entity->getId())),
                'method' => 'PUT',
            )
        );
        $form->remove('institution');
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing InstitutionDesigns entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $institutionId, InstitutionDesign $entity)
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
            $entity->setContent(
                $this->prepareDesignContent($entity->getEditableContent())
            );
            $entity->setInstitution($institution);

            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('ojs_institution_manager_design_edit', [
                'id' => $entity->getId(),
                'institutionId' => $institution->getId()
                ]
            );
        }

        return $this->render(
            'OjsJournalBundle:ManagerInstitutionDesign:edit.html.twig',
            array(
                'entity' => $entity,
                'institution' => $institution,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request                                            $request
     * @param  InstitutionDesign                                   $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws TokenNotFoundException
     */
    public function deleteAction(Request $request, InstitutionDesign $entity, $institutionId)
    {
        $em = $this->getDoctrine()->getManager();
        $institution = $em->getRepository('OjsJournalBundle:Institution')->find($institutionId);
        $this->throw404IfNotFound($institution);
        if (!$this->isGrantedForInstitution($institution)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_institution_manager_design'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_institution_manager_design_index', ['institutionId' => $institutionId]);
    }

    /**
     * @param  String                                            $editableContent
     * @return String
     */
    private function prepareDesignContent($editableContent)
    {
        $editableContent = preg_replace_callback(
            '/<span\s*class\s*=\s*"\s*design-hide-block[^"]*"[^>]*>.*<\s*\/\s*span\s*>.*<span\s*class\s*=\s*"\s*design-hide-endblock[^"]*"[^>]*>.*<\s*\/\s*span\s*>/Us',
            function($matches)
            {
                preg_match('/<!---.*--->/Us', $matches[0], $matched);
                return str_ireplace(['<!---', '--->'], '', $matched[0]);
            },
            $editableContent
        );
        $editableContent = preg_replace_callback(
            '/<span\s*class\s*=\s*"\s*design-hide-span[^"]*"[^>]*>.*<\s*\/\s*span\s*>/Us',
            function($matches)
            {
                preg_match('/<!---.*--->/Us', $matches[0], $matched);
                return str_ireplace(['<!---', '--->'], '', $matched[0]);
            },
            $editableContent
        );
        $editableContent = preg_replace_callback(
            '/<span\s*class\s*=\s*"\s*design-inline[^"]*"[^>]*>.*<\s*\/\s*span\s*>/Us',
            function($matches)
            {
                preg_match('/title\s*=\s*"\s*{.*}\s*"/Us', $matches[0], $matched);
                $matched[0] = preg_replace('/title\s*=\s*"/Us', '', $matched[0]);
                return str_replace('"', '', $matched[0]);
            },
            $editableContent
        );
        $editableContent = str_ireplace('<!--gm-editable-region-->', '', $editableContent);
        $editableContent = str_ireplace('<!--/gm-editable-region-->', '', $editableContent);
        return $editableContent;
    }

    /**
     * @param  String                                            $editableContent
     * @return String
     */
    private function prepareEditContent($editableContent)
    {
        $editableContent = str_ireplace('<!--raw-->', '{% raw %}<!--raw-->', $editableContent);
        $editableContent = str_ireplace('<!--endraw-->', '{% endraw %}<!--endraw-->', $editableContent);

        $editableContent = preg_replace_callback(
            '/<span\s*class\s*=\s*"\s*design-inline[^"]*"[^>]*>.*<\s*\/\s*span\s*>/Us',
            function($matches)
            {
                return preg_replace_callback(
                    '/{{.*}}/Us',
                    function($matched)
                    {
                        return '{{ "'.addcslashes($matched[0], '"').'" }}';
                    },
                    $matches[0]
                );
            },
            $editableContent
        );
        return $editableContent;
    }
}
