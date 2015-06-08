<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Helper\ActionHelper;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Form\JournalType;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * Journal controller.
 */
class JournalController extends Controller
{

    /**
     * @param Request $request
     * @param $journal_id
     * @return RedirectResponse
     */
    public function changeSelectedAction(Request $request, $journal_id)
    {
        $em =  $this->getDoctrine()->getManager();
        $route = $this->get('router')->generate('dashboard');
        if($request->query->get('submission', false) === '1') {
            $route = $this->get('router')->generate('article_submission_new');
        }
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($journal_id);
        $this->throw404IfNotFound($journal);
        $this->get('ojs.journal_service')->setSelectedJournal($journal);
        return $this->redirect($route);
    }

    /**
     * @return Response
     */
    public function indexAction()
    {
        if(!$this->isGranted('VIEW', new Journal())) {
            throw new AccessDeniedException("You not authorized for list journals!");
        }
        $source = new Entity('OjsJournalBundle:Journal');
        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        ActionHelper::setup($this->get('security.csrf.token_manager'), $this->get('translator'));

        $rowAction[] = ActionHelper::showAction('journal_show', 'id');
        $rowAction[] = ActionHelper::editAction('journal_edit', 'id');
        $rowAction[] = ActionHelper::cmsAction();
        $rowAction[] = ActionHelper::deleteAction('journal_delete', 'id');
        $rowAction[] = (new RowAction('Manage', 'change_selected_journal'))
            ->setRouteParameters('id')
            ->setRouteParametersMapping(array('id' => 'journal_id'))
            ->setAttributes(array('class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip', 'title' => "Manage"));

        $actionColumn->setRowActions($rowAction);

        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:Journal:index.html.twig', $data);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $entity = new Journal();
        if (!$this->isGranted('CREATE', $entity)) {
            throw new AccessDeniedException("You not authorized for create a journal!");
        }
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $header = $request->request->get('header');
            $cover = $request->request->get('cover');
            $logo = $request->request->get('logo');
            if ($header) {
                $entity->setHeaderOptions(json_encode($header));
            }
            if ($cover) {
                $entity->setImageOptions(json_encode($cover));
            }
            if ($logo) {
                $entity->setLogoOptions(json_encode($logo));
            }
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirect($this->generateUrl('journal_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsJournalBundle:Journal:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Journal entity.
     * @param  Journal                      $entity The entity
     * @return Form The form
     */
    private function createCreateForm(Journal $entity)
    {
        $form = $this->createForm(new JournalType(), $entity, array(
            'action' => $this->generateUrl('journal_create'),
            'tagEndPoint' => $this->generateUrl('api_get_tags'),
            'method' => 'POST'
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Journal entity.
     *
     * @return Response
     */
    public function newAction()
    {
        $entity = new Journal();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:Journal:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Journal entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Journal')->find($id);
        $this->throw404IfNotFound($entity);
        if(!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException("You not authorized for view this journal!");
        }

        return $this->render('OjsJournalBundle:Journal:show.html.twig', array(
            'entity' => $entity, ));
    }

    /**
     * Displays a form to edit an existing Journal entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Journal $entity */
        $entity = $em->getRepository('OjsJournalBundle:Journal')->find($id);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You not authorized for edit this journal!");
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:Journal:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Journal entity.
     * @param  Journal                      $entity The entity
     * @return Form The form
     */
    private function createEditForm(Journal $entity)
    {
        $form = $this->createForm(new JournalType(), $entity, array(
            'action' => $this->generateUrl('journal_update', array('id' => $entity->getId())),
            'tagEndPoint' => $this->generateUrl('api_get_tags'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Journal $entity */
        $entity = $em->getRepository('OjsJournalBundle:Journal')->find($id);

        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You not authorized for edit this journal!");
        }
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $header = $request->request->get('header');
            $cover = $request->request->get('cover');
            $logo = $request->request->get('logo');
            if ($header) {
                $entity->setHeaderOptions(json_encode($header));
            }
            if ($cover) {
                $entity->setImageOptions(json_encode($cover));
            }
            if ($logo) {
                $entity->setLogoOptions(json_encode($logo));
            }
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect($this->generateUrl('journal_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:Journal:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Journal')->find($id);

        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You not authorized for delete this journal!");
        }
        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('journal'.$id);
        if($token!=$request->get('_token'))
            throw new TokenNotFoundException("Token Not Found!");
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('journal');
    }

    /**
     * @return Response
     */
    public function applyAction()
    {
        return $this->render('OjsJournalBundle:Journal:apply.html.twig', array());
    }
}
