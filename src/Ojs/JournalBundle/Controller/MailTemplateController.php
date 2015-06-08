<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Column\NumberColumn;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Source\Vector;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Helper\ActionHelper;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ojs\JournalBundle\Entity\MailTemplate;
use Ojs\JournalBundle\Form\MailTemplateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Yaml\Parser;
use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * MailTemplate controller.
 *
 */
class MailTemplateController extends Controller
{

    /**
     * Lists all MailTemplate entities.
     *
     * @return Response
     */
    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('VIEW', $journal, 'mailTemplate')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $source = new Entity('OjsJournalBundle:MailTemplate');
        $source->manipulateRow(function (Row $row) {
            if ($row->getField("title") and strlen($row->getField('title')) > 20) {
                $row->setField('title', substr($row->getField('title'), 0, 20)."...");
            }

            return $row;
        });
        /** @var User $user */
        $user = $this->getUser();
        $ta = $source->getTableAlias();
        $source->manipulateQuery(function (QueryBuilder $qb) use ($journal, $user, $ta) {
            if ($user->isAdmin()) {
                return $qb;
            }

            return $qb->andWhere(
                $qb->expr()->eq("$ta.journalId", ':journal')
            )
                ->setParameter('journal', $journal->getId());
        });
        $grid = $this->get('grid.manager');

        $db_templates = $grid->createGrid('db_templates');
        $db_templates->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction = [];
        ActionHelper::setup($this->get('security.csrf.token_manager'), $this->get('translator'));

        $rowAction[] = ActionHelper::showAction('mailtemplate_manager_show', 'id');
        $rowAction[] = ActionHelper::editAction('mailtemplate_manager_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('mailtemplate_manager_delete', 'id');
        $actionColumn->setRowActions($rowAction);
        $db_templates->addColumn($actionColumn);

        $data = [];
        $data['db_templates'] = $db_templates;

        $yamlParser = new Parser();
        $defaultTemplates = $yamlParser->parse(file_get_contents(
            $this->container->getParameter('kernel.root_dir').
            '/../src/Ojs/JournalBundle/Resources/data/mailtemplates.yml'
        ));
        $source = new Vector($defaultTemplates, [
            new NumberColumn(['id' => 'id', 'field' => 'id', 'title' => 'ID', 'source' => true]),
            new TextColumn(['id' => 'subject', 'field' => 'subject', 'title' => 'mailtemplate.subject', 'source' => true]),
            new TextColumn(['id' => 'lang', 'field' => 'lang', 'title' => 'mailtemplate.language', 'source' => true]),
            new TextColumn(['id' => 'type', 'field' => 'type', 'title' => 'mailtemplate.title', 'source' => true]),
            new TextColumn(['id' => 'template', 'field' => 'template', 'title' => 'mailtemplate.template', 'source' => true, 'visible' => false]),
        ]);
        $defaultTemplates = $grid->createGrid('default_templates');
        $defaultTemplates->setSource($source);
        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction = [];
        $rowAction[] = ActionHelper::copyAction('mailtemplate_manager_copy', 'id');
        $actionColumn->setRowActions($rowAction);
        $defaultTemplates->addColumn($actionColumn);

        $data['default_templates'] = $defaultTemplates;

        return $grid->getGridManagerResponse('OjsJournalBundle:MailTemplate:index.html.twig', $data);
    }

    /**
     * Creates a new MailTemplate entity.
     *
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('CREATE', $journal, 'mailTemplate')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $entity = new MailTemplate();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('mailtemplate_manager_show', [
                'id' => $entity->getId(),
                ]
            );
        }

        return $this->render('OjsJournalBundle:MailTemplate:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a MailTemplate entity.
     *
     * @param MailTemplate $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(MailTemplate $entity)
    {
        $form = $this->createForm(new MailTemplateType(), $entity, array(
            'action' => $this->generateUrl('mailtemplate_manager_create'),
            'method' => 'POST',
            'user' => $this->getUser(),
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new MailTemplate entity.
     *
     * @return Response
     */
    public function newAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('CREATE', $journal, 'mailTemplate')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $entity = new MailTemplate();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:MailTemplate:'.($user->isAdmin() ? 'admin/' : '').'new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a MailTemplate entity.
     *
     * @param MailTemplate $entity
     * @return Response
     */
    public function showAction(MailTemplate $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('VIEW', $journal, 'mailTemplate')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        return $this->render('OjsJournalBundle:MailTemplate:show.html.twig', array(
            'entity' => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing MailTemplate entity.
     *
     * @param MailTemplate $entity
     * @return Response
     */
    public function editAction(MailTemplate $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('EDIT', $journal, 'mailTemplate')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $editForm = $this->createEditForm($entity);
        return $this->render('OjsJournalBundle:MailTemplate:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a MailTemplate entity.
     *
     * @param MailTemplate $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(MailTemplate $entity)
    {
        $form = $this->createForm(new MailTemplateType(), $entity, array(
            'action' => $this->generateUrl('mailtemplate_manager_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'user' => $this->getUser(),
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing MailTemplate entity.
     *
     * @param Request $request
     * @param MailTemplate $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, MailTemplate $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('EDIT', $journal, 'mailTemplate')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('mailtemplate_manager_edit', [
                'id' => $entity->getId()
                ]
            );
        }

        return $this->render('OjsJournalBundle:MailTemplate:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }


    /**
     * @param Request $request
     * @param MailTemplate $entity
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, MailTemplate $entity)
    {
        /** @var User $user */
        $user = $this->getUser();
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('DELETE', $journal, 'mailTemplate')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $em = $this->getDoctrine()->getManager();

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('mailtemplate_manager'.$entity->getId());
        if($token!=$request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }

        $em->remove($entity);
        $em->flush();

        $this->successFlashBag('successful.remove');

        return $this->redirect($this->generateUrl($user->isAdmin() ? 'mailtemplate' : 'mailtemplate_manager'));
    }

    /**
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function copyAction(Request $request, $id)
    {
        /** @var User $user */
        $user = $this->getUser();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        $entity = new MailTemplate();

        $yamlParser = new Parser();
        $defaultTemplates = $yamlParser->parse(file_get_contents(
            $this->container->getParameter('kernel.root_dir').
            '/../src/Ojs/JournalBundle/Resources/data/mailtemplates.yml'
        ));
        $template = [];
        foreach ($defaultTemplates as $temp) {
            if ($temp['id'] == $id) {
                $template = $temp;
                break;
            }
        }

        $entity
            ->setLang($template['lang'])
            ->setSubject($template['subject'])
            ->setTemplate(str_replace('<br>', "\n", $template['template']))
            ->setType($template['type']);
        if (!$user->isAdmin()) {
            $entity->setJournal($journal);
        }
        $form = $this->createCreateForm($entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mailtemplate'.($user->isAdmin() ? '' : '_manager').'_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsJournalBundle:MailTemplate:'.($user->isAdmin() ? 'admin/' : '').'new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }
}
