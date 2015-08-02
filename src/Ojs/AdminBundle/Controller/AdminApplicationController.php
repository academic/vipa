<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Ojs\AdminBundle\Form\Type\InstitutionApplicationType;
use Ojs\AdminBundle\Form\Type\JournalApplicationType;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\Common\Params\CommonParams;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\Lang;
use Ojs\JournalBundle\Entity\Subject;
use Ojs\LocationBundle\Entity\Country;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * Institution controller.
 *
 */
class AdminApplicationController extends Controller
{
    /**
     * Lists all Institution entities.
     *
     */
    public function institutionIndexAction()
    {
        $data = array();
        $source = new Entity('OjsJournalBundle:Institution', 'application');
        $tableAlias = $source->getTableAlias();

        $source->manipulateQuery(
            function (QueryBuilder $query) use ($tableAlias) {
                $query->andWhere($tableAlias . ".status = :status")
                    ->setParameter('status', 0);
                return $query;
            }
        );

        $source->addHint(Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $grid->getColumn('status')->manipulateRenderCell(
            function ($value, $row, $router) {
                return $this->get('translator')->trans(CommonParams::institutionStatus($row->getField('status')));
            }
        );
        $rowAction = array();
        $rowAction[] = $gridAction->editAction('ojs_admin_application_institution_edit', 'id');
        $rowAction[] = $gridAction->showAction('ojs_admin_application_institution_show', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_application_institution_delete', 'id');
        $actionColumn = new ActionsColumn("actions", 'actions');
        $actionColumn->setRowActions($rowAction);

        $grid->addColumn($actionColumn);
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsAdminBundle:AdminApplication:institution.html.twig', $data);
    }

    public function journalIndexAction()
    {
        $data = array();
        $source = new Entity('OjsJournalBundle:Journal');
        $alias = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $query) use ($alias) {
                $query
                    ->andWhere($alias . '.status = :status')
                    ->setParameter('status', '0');
                return $query;
            }
        );

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');
        $rowAction = array();
        $rowAction[] = $gridAction->editAction('ojs_admin_application_journal_edit', 'id');
        $rowAction[] = $gridAction->showAction('ojs_admin_application_journal_show', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_application_journal_delete', 'id');
        $actionColumn = new ActionsColumn("actions", 'actions');
        $actionColumn->setRowActions($rowAction);

        $grid->addColumn($actionColumn);
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsAdminBundle:AdminApplication:journal.html.twig', $data);
    }

    public function journalDetailAction($id)
    {
        /** @var EntityManager $em */

        $em = $this->getDoctrine()->getManager();
        $entity = $this->getDoctrine()->getRepository('OjsJournalBundle:Journal')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $data = [];
        $languages = [];
        $subjects = [];

        /** @var Lang $lang */
        foreach ($entity->getLanguages() as $key => $language) {
            $lang = $em->find('OjsJournalBundle:Lang', $language);
            $languages[] = $lang->getName();
        }

        /** @var Subject $subj */
        foreach ($entity->getSubjects() as $subject) {
            $subj = $em->find('OjsJournalBundle:Subject', $subject);
            $subjects[] = "{$subj->getSubject()}";
        }

        /** @var Institution $institution */
        $institution = $em->find('OjsJournalBundle:Institution', $entity->getInstitution());

        /** @var Country $country */
        $country = $em->find('OjsLocationBundle:Country', $entity->getCountry());

        $data['entity'] = $entity;
        $data['languages'] = implode(',', $languages);
        $data['institution'] = $institution->getName() . "[" . $institution->getSlug() . "]";
        $data['country'] = $country->getName();
        $data['subjects'] = implode(',', $subjects);

        return $this->render('OjsAdminBundle:AdminApplication:journal_detail.html.twig', $data);
    }

    public function institutionDetailAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Institution')->findOneBy(
            array(
                'verified' => false,
                'id' => $id,
            )
        );
        if (!$entity) {
            throw new NotFoundHttpException();
        }

        return $this->render('OjsAdminBundle:AdminApplication:institution_detail.html.twig', array('entity' => $entity));
    }

    public function journalEditAction($id)
    {
        $entity = $this->getDoctrine()->getRepository('OjsJournalBundle:Journal')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(
            new JournalApplicationType(), $entity,
            ['action' => $this->generateUrl('ojs_admin_application_journal_update', array('id' => $entity->getId()))]
        )
            ->add('update', 'submit');

        return $this->render('OjsAdminBundle:AdminApplication:journal_edit.html.twig', [
            'form' => $form->createView(),
            'entity' => $entity
        ]);
    }

    public function institutionEditAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Institution $entity */
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(
            new InstitutionApplicationType(),
            $entity,
            [
                'action' => $this->generateUrl('ojs_admin_application_institution_update', array('id' => $entity->getId())),
            ]
        )
        ->add('update','submit');

        return $this->render(
            'OjsAdminBundle:AdminApplication:institution_edit.html.twig',
            ['form' => $form->createView(), 'entity' => $entity]
        );
    }

    public function journalUpdateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->getDoctrine()->getRepository('OjsJournalBundle:Journal')->find($id);
        $this->throw404IfNotFound($entity);

        $form = $this->createForm(
            new JournalApplicationType(),
            $entity
        )
        ->add('update', 'submit');

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect($this->generateUrl('ojs_admin_application_journal_index'));
        }

        return $this->render('OjsAdminBundle:AdminApplication:journal_edit.html.twig', ['entity' => $entity, 'form' => $form->createView()]);
    }

    public function institutionUpdateAction(Request $request, $id)
    {
        /** @var Institution $entity */
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($id);

        $this->throw404IfNotFound($entity);

        $form = $this->createForm(new InstitutionApplicationType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect($this->generateUrl('ojs_admin_application_institution_index'));
        }

        return $this->render(
            'OjsAdminBundle:AdminApplication:institution_edit.html.twig',
            ['form' => $form->createView(), 'entity' => $entity]
        );
    }

    public function journalDeleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->getDoctrine()->getRepository('OjsJournalBundle:Journal')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_application' . $id);

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->get('router')->generate('ojs_admin_application_journal_index'));
    }

    public function institutionDeleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Institution $entity */
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($id);

        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_application' . $id);
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->get('router')->generate('ojs_admin_application_institution_index'));
    }

    public function journalSaveAction($id)
    {
        /** @var Journal $entity */
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Journal')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $entity->setStatus(1);
        return $this->redirect($this->get('router')->generate('ojs_admin_application_journal_edit', ['id' => $entity->getId()]));
    }

    public function institutionSaveAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Institution $entity */
        $entity = $em->getRepository('OjsJournalBundle:Institution')->find($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        $entity->setVerified(true);

        return $this->redirect($this->get('router')->generate('ojs_admin_application_institution_edit', ['id' => $entity->getId()]));
    }
}
