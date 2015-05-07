<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Document;
use Doctrine\ODM\MongoDB\Query\Builder;
use Doctrine\ORM\EntityManager;
use Ojs\Common\Helper\ActionHelper;
use Ojs\JournalBundle\Document\JournalSuggestion;
use Ojs\JournalBundle\Document\InstituteSuggestion;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\Lang;
use Ojs\JournalBundle\Entity\Subject;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Institution;
use Okulbilisim\LocationBundle\Entity\Location;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Institution controller.
 *
 */
class SuggestionController extends Controller
{
    /**
     * Lists all Institution entities.
     *
     */
    public function instituteAction()
    {
        $source = new Document('OjsJournalBundle:InstituteSuggestion');
        $source->manipulateQuery(function(Builder $query){
            $query->where("typeof(this.merged)=='undefined'");
            return $query;
        });
        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = ActionHelper::showAction('suggestion_institute_show', 'id');
        $rowAction[] = ActionHelper::deleteAction('suggestion_institute_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;
        return $grid->getGridResponse('OjsJournalBundle:Suggestion:institute.html.twig',$data);
    }

    public function journalAction()
    {

        $source = new Document('OjsJournalBundle:JournalSuggestion');
        $source->manipulateQuery(function(Builder $query){
            $query->where("typeof(this.merged)=='undefined'");
            return $query;
        });
        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = ActionHelper::showAction('suggestion_journal_show', 'id');
        $rowAction[] = ActionHelper::deleteAction('suggestion_journal_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;
        return $grid->getGridResponse('OjsJournalBundle:Suggestion:journal.html.twig',$data);
    }

    public function journalDetailAction($id)
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        /** @var JournalSuggestion $entity */
        $entity = $dm->find('OjsJournalBundle:JournalSuggestion', $id);
        if (!$entity) {
            throw new NotFoundHttpException;
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
        $country = $em->find('OkulbilisimLocationBundle:Location',$entity->getCountry());

        $data['entity'] = $entity;
        $data['languages'] = join(',', $languages);
        $data['institution'] = $institution->getName() . "[" . $institution->getSlug() . "]";
        $data['country'] = $country->getName();
        $data['subjects'] = join(',', $subjects);
        return $this->render('OjsJournalBundle:Suggestion:journal_detail.html.twig', $data);
    }

    public function instituteDetailAction($id)
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $entity = $dm->find('OjsJournalBundle:InstituteSuggestion', $id);
        if (!$entity) {
            throw new NotFoundHttpException;
        }

        $data = [];
        $data['entity'] = $entity;

        return $this->render('OjsJournalBundle:Suggestion:institute_detail.html.twig', $data);

    }

    public function journalDeleteAction($id)
    {

        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $entity = $dm->find('OjsJournalBundle:JournalSuggestion', $id);
        if (!$entity) {
            throw new NotFoundHttpException;
        }
        $dm->remove($entity);
        $dm->flush();
        return $this->redirect($this->get('router')->generate('journal_suggestion'));
    }

    public function instituteDeleteAction($id)
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $entity = $dm->find('OjsJournalBundle:InstituteSuggestion', $id);
        if (!$entity) {
            throw new NotFoundHttpException;
        }
        $dm->remove($entity);
        $dm->flush();
        return $this->redirect($this->get('router')->generate('institute_suggestion'));

    }


    public function journalSaveAction($id)
    {
        try {
            $dm = $this->get('doctrine.odm.mongodb.document_manager');
            /** @var JournalSuggestion $entity */
            $entity = $dm->find('OjsJournalBundle:JournalSuggestion', $id);
            if (!$entity) {
                throw new NotFoundHttpException;
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
            foreach ($entity->getLanguages() as $lang) {
                $journal->addLanguage($em->find('OjsJournalBundle:Lang', $lang));
            }
            $em->persist($journal);
            $em->flush();
            $entity->setMerged(true);
            $dm->persist($entity);
            $dm->flush();
            return $this->redirect($this->get('router')->generate('journal_edit',['id'=>$journal->getId()]));

        } catch (\Exception $e) {
            $session = $this->get('session');
            $session->getFlashBag()->add('error', $e->getMessage());
            $session->save();
            return $this->redirect($this->get('router')->generate('suggestion_journal_show', ['id' => $id]));

        }
    }

    public function instituteSaveAction($id)
    {
        try {
            $dm = $this->get('doctrine.odm.mongodb.document_manager');
            /** @var InstituteSuggestion $entity */
            $entity = $dm->find('OjsJournalBundle:InstituteSuggestion', $id);
            if (!$entity) {
                throw new NotFoundHttpException;
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
            return $this->redirect($this->get('router')->generate('suggestion_institute_show', ['id' => $id]));

        }
    }

}
