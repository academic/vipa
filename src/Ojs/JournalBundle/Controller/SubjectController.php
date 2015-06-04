<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Helper\ActionHelper;
use Ojs\JournalBundle\Entity\SubjectRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Subject;
use Ojs\JournalBundle\Form\SubjectType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;

/**
 * Subject controller.
 *
 */
class SubjectController extends Controller
{

    /**
     * Lists all Subject entities.
     *
     */
    public function indexAction()
    {
        $source = new Entity("OjsJournalBundle:Subject");
        $grid = $this->get('grid')->setSource($source);
        $actionColumn = new ActionsColumn("actions", 'actions');

        ActionHelper::setup($this->get('security.csrf.token_manager'));
        $rowAction[] = ActionHelper::showAction('subject_show', 'id');
        $rowAction[] = ActionHelper::editAction('subject_edit', 'id');
        $rowAction[] = ActionHelper::showAction('subject_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $data = [];
        $data['grid'] = $grid;
        /** @var SubjectRepository $repo */
        $repo = $this->getDoctrine()->
                getRepository('OjsJournalBundle:Subject');
        $options =  array(
            'decorate' => true,
            'rootOpen' => '<ul>',
            'rootClose' => '</ul>',
            'childOpen' => '<li>',
            'childClose' => '</li>',
            'idField' => true,
            'nodeDecorator' => function ($node) {
                return '<a href="'.$this->generateUrl('subject_show', array('id' => $node['id'])).'">'.$node['subject'].'</a>';
            }, );
        $data['htmlTree'] = $repo->childrenHierarchy(null, false, $options);

        return $grid->getGridResponse('OjsJournalBundle:Subject:index.html.twig', $data);
    }

    /**
     * Creates a new Subject entity.
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $entity = new Subject();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setTranslatableLocale($request->getLocale());
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('subject_show', ['id' => $entity->getId()]);
        }

        return $this->render('OjsJournalBundle:Subject:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Subject entity.
     *
     * @param Subject $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Subject $entity)
    {
        $form = $this->createForm(new SubjectType(), $entity, array(
            'action' => $this->generateUrl('subject_create'),
            'tagEndPoint' => $this->generateUrl('api_get_tags'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Subject entity.
     *
     */
    public function newAction()
    {
        $entity = new Subject();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:Subject:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Subject entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Subject')->find($id);
        $this->throw404IfNotFound($entity);

        return $this->render('OjsJournalBundle:Subject:show.html.twig', array(
                    'entity' => $entity, ));
    }

    /**
     * Displays a form to edit an existing Subject entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Subject $entity */
        $entity = $em->getRepository('OjsJournalBundle:Subject')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:Subject:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Subject entity.
     *
     * @param Subject $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Subject $entity)
    {
        $form = $this->createForm(new SubjectType(), $entity, array(
            'action' => $this->generateUrl('subject_update', array('id' => $entity->getId())),
            'apiRoot' => $this->generateUrl('ojs_api_homepage'),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Subject entity.
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Subject $entity */
        $entity = $em->getRepository('OjsJournalBundle:Subject')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('subject_edit', ['id' => $id]);
        }

        return $this->render('OjsJournalBundle:Subject:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws TokenNotFoundException
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Subject')->find($id);
        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('subject'.$id);
        if($token!=$request->get('_token'))
            throw new TokenNotFoundException("Token Not Found!");

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('subject');
    }
}
