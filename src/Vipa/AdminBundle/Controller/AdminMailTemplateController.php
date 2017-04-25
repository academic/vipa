<?php

namespace Vipa\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Vipa\AdminBundle\Form\Type\MailTemplateType;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\JournalBundle\Entity\MailTemplate;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * MailTemplate controller.
 *
 */
class AdminMailTemplateController extends Controller
{
    /**
     * Lists all MailTemplate entities.
     *
     */
    public function indexAction()
    {
        $source = new Entity('VipaJournalBundle:MailTemplate');
        $tableAlias = $source->getTableAlias();
        $source->manipulateQuery(
            function ($query) use ($tableAlias) {
                $query->andWhere($tableAlias . '.journal IS NULL');
            }
        );
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('vipa_admin_mail_template_show', 'id');
        $rowAction[] = $gridAction->editAction('vipa_admin_mail_template_edit', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('VipaAdminBundle:AdminMailTemplate:index.html.twig', $data);
    }

    /**
     * Finds and displays a MailTemplate entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('VipaJournalBundle:MailTemplate')->find($id);
        $this->throw404IfNotFound($entity);

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_admin_mail_template' . $entity->getId());

        return $this->render(
            'VipaAdminBundle:AdminMailTemplate:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing MailTemplate entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $mailEventsChain = $this->get('vipa_core.mail.event_chain');
        $em = $this->getDoctrine()->getManager();
        /** @var MailTemplate $entity */
        $entity = $em->getRepository('VipaJournalBundle:MailTemplate')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        $eventDetail = $mailEventsChain->getEventOptionsByName($entity->getType());
        $eventParamsAsString = $mailEventsChain->getEventParamsAsString($eventDetail);
        return $this->render(
            'VipaAdminBundle:AdminMailTemplate:edit.html.twig',
            array(
                'entity' => $entity,
                'form' => $editForm->createView(),
                'eventDetail' => $eventDetail,
                'eventParamsAsString' => $eventParamsAsString,
            )
        );
    }

    /**
     * Creates a form to edit a MailTemplate entity.
     *
     * @param MailTemplate $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(MailTemplate $entity)
    {
        $form = $this->createForm(
            new MailTemplateType($entity->getId()),
            $entity,
            array(
                'action' => $this->generateUrl('vipa_admin_mail_template_update', array('id' => $entity->getId())),
                'method' => 'PUT'
            )
        );
        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * Edits an existing MailTemplate entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var MailTemplate $entity */
        $entity = $em->getRepository('VipaJournalBundle:MailTemplate')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('vipa_admin_mail_template_edit', ['id' => $id]);
        }

        return $this->render(
            'VipaAdminBundle:AdminMailTemplate:edit.html.twig',
            array(
                'entity' => $entity,
                'form' => $editForm->createView(),
            )
        );
    }
}
