<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Form\InstitutionType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
        if(!$this->isGranted('VIEW', new Institution())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $source = new Entity('OjsJournalBundle:Institution');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('institution_show', 'id');
        $rowAction[] = $gridAction->editAction('institution_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('institution_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:Institution:index.html.twig', $data);
    }

    /**
     * Creates a new Institution entity.
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        if(!$this->isGranted('CREATE', new Institution())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new Institution();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $header = $request->request->get('header');
            $cover = $request->request->get('logo');
            if ($header) {
                $entity->setHeaderOptions(json_encode($header));
            }
            if ($cover) {
                $entity->setLogoOptions(json_encode($cover));
            }
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('institution_show', ['id' => $entity->getId()]);
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
            'tagEndPoint' => $this->generateUrl('api_get_tags'),
            'institutionsEndPoint' => $this->generateUrl('api_get_institutions'),
            'institutionEndPoint' => $this->generateUrl('api_get_institution'),
            'helper' => $this->get('okulbilisim_location.form.helper'),
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Institution entity.
     *
     */
    public function newAction()
    {
        if(!$this->isGranted('CREATE', new Institution())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
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
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($id);
        if(!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);

        return $this->render('OjsJournalBundle:Institution:show.html.twig', array(
            'entity' => $entity, ));
    }

    /**
     * Displays a form to edit an existing Institution entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Institution $entity */
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($id);
        $this->throw404IfNotFound($entity);
        if(!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
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
            'tagEndPoint' => $this->generateUrl('api_get_tags'),
            'institutionsEndPoint' => $this->generateUrl('api_get_institutions'),
            'institutionEndPoint' => $this->generateUrl('api_get_institution'),
            'helper' => $this->get('okulbilisim_location.form.helper'),
        ));

        return $form;
    }

    /**
     * Edits an existing Institution entity.
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Institution $entity */
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($id);
        if(!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $header = $request->request->get('header');
            $cover = $request->request->get('logo');
            if ($header) {
                $entity->setHeaderOptions(json_encode($header));
            }
            if ($cover) {
                $entity->setLogoOptions(json_encode($cover));
            }
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('institution_edit', ['id' => $id]);
        }

        return $this->render('OjsJournalBundle:Institution:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($id);
        if(!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('institution'.$id);
        if($token!=$request->get('_token'))
            throw new TokenNotFoundException("Token Not Found!");

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirect($this->generateUrl('institution'));
    }
}
