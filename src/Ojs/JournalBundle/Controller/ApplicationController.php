<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\Common\Params\CommonParams;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Document;
use APY\DataGridBundle\Grid\Row;
use Doctrine\ODM\MongoDB\Query\Builder;
use Doctrine\ORM\EntityManager;
use Ojs\Common\Helper\ActionHelper;
use Ojs\JournalBundle\Document\JournalApplication;
use Ojs\JournalBundle\Document\InstitutionApplication;
use Ojs\JournalBundle\Entity\Contact;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalContact;
use Ojs\JournalBundle\Entity\Lang;
use Ojs\JournalBundle\Entity\Subject;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Form\InstitutionApplicationType;
use Ojs\JournalBundle\Form\JournalApplicationType;
use Okulbilisim\LocationBundle\Entity\Location;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * Institution controller.
 *
 */
class ApplicationController extends Controller
{
    /**
     * Lists all Institution entities.
     *
     */
    public function institutionIndexAction()
    {
        $source = new Document('OjsJournalBundle:InstitutionApplication');
        $source->manipulateQuery(function (Builder $query) {
            $query->where("typeof(this.merged) == 'undefined'");

            return $query;
        });

        $source->manipulateRow(
            function (Row $row) {
                $status = $row->getField('status');
                $text = $this->get('translator')->trans(CommonParams::institutionApplicationStatus($status));
                $row->setField('status', $text);
                return $row;
            });

        $grid = $this->get('grid')->setSource($source);
        ActionHelper::setup($this->get('security.csrf.token_manager'));

        $rowAction[] = ActionHelper::editAction('application_institution_edit', 'id');
        $rowAction[] = ActionHelper::showAction('application_institution_show', 'id');
        $rowAction[] = ActionHelper::deleteAction('application_institution_delete', 'id');
        $actionColumn = new ActionsColumn("actions", 'actions');
        $actionColumn->setRowActions($rowAction);

        $grid->addColumn($actionColumn);
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:Application:institution.html.twig', $data);
    }

    public function journalIndexAction()
    {
        $source = new Document('OjsJournalBundle:JournalApplication');
        $source->manipulateQuery(
            function(Builder $query) {
                $query->where("typeof(this.merged) == 'undefined'");
                return $query;
        });

        $source->manipulateRow(
            function (Row $row) {
                $status = $row->getField('status');
                $text = $this->get('translator')->trans(CommonParams::journalApplicationStatus($status));
                $row->setField('status', $text);
                return $row;
        });

        $grid = $this->get('grid')->setSource($source);
        ActionHelper::setup($this->get('security.csrf.token_manager'));

        $rowAction[] = ActionHelper::editAction('application_journal_edit', 'id');
        $rowAction[] = ActionHelper::showAction('application_journal_show', 'id');
        $rowAction[] = ActionHelper::deleteAction('application_journal_delete', 'id');
        $actionColumn = new ActionsColumn("actions", 'actions');
        $actionColumn->setRowActions($rowAction);

        $grid->addColumn($actionColumn);
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:Application:journal.html.twig', $data);
    }

    public function journalDetailAction($id)
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        /** @var JournalApplication $entity */
        $entity = $dm->find('OjsJournalBundle:JournalApplication', $id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $data = [];
        $languages = [];
        foreach ($entity->getLanguages() as $key => $language) {
            /** @var Lang $lang */
            $lang = $em->find('OjsJournalBundle:Lang', $language);
            $languages[] = $lang->getName();
        }

        $subjects = [];
        foreach ($entity->getSubjects() as $subject) {
            /** @var Subject $subj */
            $subj = $em->find('OjsJournalBundle:Subject', $subject);
            $subjects[] = "{$subj->getSubject()}";
        }

        /** @var Institution $institution */
        $institution = $em->find('OjsJournalBundle:Institution', $entity->getInstitution());

        /** @var Location $country */
        $country = $em->find('OkulbilisimLocationBundle:Location', $entity->getCountry());

        $data['entity'] = $entity;
        $data['languages'] = implode(',', $languages);
        $data['institution'] = $institution->getName()."[".$institution->getSlug()."]";
        $data['country'] = $country->getName();
        $data['subjects'] = implode(',', $subjects);

        return $this->render('OjsJournalBundle:Application:journal_detail.html.twig', $data);
    }

    public function institutionDetailAction($id)
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $entity = $dm->find('OjsJournalBundle:InstitutionApplication', $id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $data = [];
        $data['entity'] = $entity;

        return $this->render('OjsJournalBundle:Application:institution_detail.html.twig', $data);
    }

    public function journalEditAction($id)
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $document = $dm->find('OjsJournalBundle:JournalApplication', $id);

        if (!$document) {
            throw new NotFoundHttpException;
        }

        $form = $this->createForm(new JournalApplicationType(), $document, [
            'action' => $this->generateUrl('application_journal_update', array('id' => $document->getId())),
            'em' => $this->getDoctrine()->getManager()]);
        return $this->render('OjsJournalBundle:Application:journal_edit.html.twig', ['form' => $form->createView()]);

    }

    public function institutionEditAction($id)
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $document = $dm->find('OjsJournalBundle:InstitutionApplication', $id);

        if (!$document) {
            throw new NotFoundHttpException;
        }

        $form = $this->createForm(new InstitutionApplicationType(), $document, [
            'em' => $this->getDoctrine()->getManager(),
            'helper' => $this->get('okulbilisim_location.form.helper'),
            'action' => $this->generateUrl('application_institution_update', array('id' => $document->getId()))]);
        return $this->render('OjsJournalBundle:Application:institution_edit.html.twig', ['form' => $form->createView()]);

    }

    public function journalUpdateAction(Request $request, $id)
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $document = $dm->find('OjsJournalBundle:JournalApplication', $id);
        $this->throw404IfNotFound($document);

        $form = $this->createForm(new JournalApplicationType(), $document, ['em' => $this->getDoctrine()->getManager()]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $dm->flush();
            $this->successFlashBag('successful.update');
            return $this->redirect($this->generateUrl('journal_application'));
        }

        return $this->render('OjsJournalBundle:Application:journal_edit.html.twig', ['form' => $form->createView()]);
    }

    public function institutionUpdateAction(Request $request, $id)
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $document = $dm->find('OjsJournalBundle:InstitutionApplication', $id);
        $this->throw404IfNotFound($document);

        $form = $this->createForm(new InstitutionApplicationType(), $document, [
            'em' => $this->getDoctrine()->getManager(),
            'helper' => $this->get('okulbilisim_location.form.helper')]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $dm->flush();
            $this->successFlashBag('successful.update');
            return $this->redirect($this->generateUrl('institution_application'));
        }

        return $this->render('OjsJournalBundle:Application:institution_edit.html.twig', ['form' => $form->createView()]);
    }

    public function journalDeleteAction(Request $request,$id)
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $entity = $dm->find('OjsJournalBundle:JournalApplication', $id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('application_journal'.$id);
        if($token!=$request->get('_token'))
            throw new TokenNotFoundException("Token Not Found!");

        $dm->remove($entity);
        $dm->flush();

        return $this->redirect($this->get('router')->generate('journal_application'));
    }

    public function institutionDeleteAction(Request $request, $id)
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $entity = $dm->find('OjsJournalBundle:InstitutionApplication', $id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('application_institution'.$id);
        if($token!=$request->get('_token'))
            throw new TokenNotFoundException("Token Not Found!");

        $dm->remove($entity);
        $dm->flush();

        return $this->redirect($this->get('router')->generate('institution_application'));
    }

    public function journalSaveAction($id)
    {
        try {
            $dm = $this->get('doctrine.odm.mongodb.document_manager');

            /** @var JournalApplication $entity */
            $entity = $dm->find('OjsJournalBundle:JournalApplication', $id);

            if (!$entity) {
                throw new NotFoundHttpException();
            }

            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();

            /** @var \Ojs\UserBundle\Entity\User $user */
            $user = $em->find('OjsUserBundle:User', $entity->getUser());

            $journal = new Journal();
            $journal->setUrl($entity->getUrl())
                ->setTags($entity->getTags())
                ->setCountryId($entity->getCountry())
                ->setCreatedBy($user->getUsername())
                ->setDomain($entity->getDomain())
                ->setEissn($entity->getEissn())
                ->setFirstPublishDate($entity->getFirstPublishDate())
                ->setHeader($entity->getHeaderImage())
                ->setImage($entity->getCoverImage())
                ->setInstitutionId($entity->getInstitution())
                ->setIssn($entity->getIssn())
                ->setPeriod($entity->getPeriod())
                ->setDomain($entity->getDomain())
                ->setPath($entity->getPath())
                ->setSubtitle($entity->getSubtitle())
                ->setTitle($entity->getTitle())
                ->setTitleAbbr($entity->getTitleAbbr())
                ->setTitleTransliterated($entity->getTitleTransliterated());

            foreach ($entity->getSubjects() as $s) {
                /** @var Subject $subject */
                $subject = $em->find('OjsJournalBundle:Subject', $s);
                $journal->addSubject($subject);
            }

            foreach ($entity->getLanguages() as $l) {
                $lang = $em->find('OjsJournalBundle:Lang', $l);
                $journal->addLanguage($lang);
            }

            $em->persist($journal);
            $em->flush();

            $editorContact = new Contact();
            $editorContact->setFirstName($entity->getEditorName());
            $editorContact->setLastName($entity->getEditorName());
            $editorContact->setEmail($entity->getEditorEmail());
            $em->persist($editorContact);

            $assistantContact = new Contact();
            $assistantContact->setFirstName($entity->getEditorName());
            $assistantContact->setLastName($entity->getEditorName());
            $assistantContact->setEmail($entity->getEditorEmail());
            $em->persist($assistantContact);

            $techContact = new Contact();
            $techContact->setFirstName($entity->getEditorName());
            $techContact->setLastName($entity->getEditorName());
            $techContact->setEmail($entity->getEditorEmail());
            $em->persist($techContact);

            $em->flush();

            // TODO: Don't use hardcoded types.
            /** @var \Ojs\JournalBundle\Entity\ContactTypes $editorType */
            $editorType = $em->find('OjsJournalBundle:ContactTypes', 1);
            /** @var \Ojs\JournalBundle\Entity\ContactTypes $assistantType */
            $assistantType = $em->find('OjsJournalBundle:ContactTypes', 1);
            /** @var \Ojs\JournalBundle\Entity\ContactTypes $techContactType */
            $techContactType = $em->find('OjsJournalBundle:ContactTypes', 1);


            $editorRelation = new JournalContact();
            $editorRelation->setJournal($journal);
            $editorRelation->setContact($editorContact);
            $editorRelation->setContactType($editorType);
            $em->persist($editorRelation);

            $assistantRelation = new JournalContact();
            $assistantRelation->setJournal($journal);
            $assistantRelation->setContact($assistantContact);
            $assistantRelation->setContactType($assistantType);
            $em->persist($assistantRelation);

            $techContactRelation = new JournalContact();
            $techContactRelation->setJournal($journal);
            $techContactRelation->setContact($techContact);
            $techContactRelation->setContactType($techContactType);
            $em->persist($techContactRelation);

            $em->flush();

            $entity->setMerged(true);
            $dm->persist($entity);
            $dm->flush();

            return $this->redirect($this->get('router')->generate('journal_edit', ['id' => $journal->getId()]));
        } catch (\Exception $e) {
            $session = $this->get('session');
            $session->getFlashBag()->add('error', $e->getMessage());
            $session->save();

            return $this->redirect($this->get('router')->generate('application_journal_show', ['id' => $id]));
        }
    }

    public function institutionSaveAction($id)
    {
        try {
            $dm = $this->get('doctrine.odm.mongodb.document_manager');
            /** @var InstitutionApplication $entity */
            $entity = $dm->find('OjsJournalBundle:InstitutionApplication', $id);
            if (!$entity) {
                throw new NotFoundHttpException();
            }
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            /** @var \Ojs\UserBundle\Entity\User $user */
            $user = $em->find('OjsUserBundle:User', $entity->getUser());
            $institute = new Institution();
            $institute
                ->setAbout($entity->getAbout())
                ->setAddress($entity->getAddress())
                ->setAddressLat($entity->getLat())
                ->setAddressLong($entity->getLon())
                ->setCityId($entity->getCity())
                ->setCountryId($entity->getCountry())
                ->setCreatedBy($user->getUsername())
                ->setEmail($entity->getEmail())
                ->setFax($entity->getFax())
                ->setHeader($entity->getHeaderImage())
                ->setInstitutionTypeId($entity->getType())
                ->setLogo($entity->getLogoImage())
                ->setName($entity->getName())
                ->setPhone($entity->getPhone())
                ->setSlug($entity->getSlug())
                ->setTags($entity->getTags())
                ->setUrl($entity->getUrl())
                ->setWiki($entity->getWikiUrl());
            $em->persist($institute);
            $em->flush();
            $entity->setMerged(true);
            $dm->persist($entity);
            $dm->flush();

            return $this->redirect($this->get('router')->generate('institution_edit', ['id' => $institute->getId()]));
        } catch (\Exception $e) {
            $session = $this->get('session');
            $session->getFlashBag()->add('error', $e->getMessage());
            $session->save();

            return $this->redirect($this->get('router')->generate('application_institution_show', ['id' => $id]));
        }
    }
}
