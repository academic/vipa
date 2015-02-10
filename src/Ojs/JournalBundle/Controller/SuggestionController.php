<?php

namespace Ojs\JournalBundle\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Form\InstitutionType;
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
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $entities = $dm->getRepository('OjsJournalBundle:InstituteSuggestion')->findBy(['merged' => null]);
        return $this->render('OjsJournalBundle:Suggestion:institute.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function journalAction()
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $entities = $dm->getRepository('OjsJournalBundle:JournalSuggestion')->findBy(['merged' => null]);
        return $this->render('OjsJournalBundle:Suggestion:journal.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function journalDetailAction($id)
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $entity = $dm->find('OjsJournalBundle:JournalSuggestion', $id);
        if (!$entity) {
            throw new NotFoundHttpException;
        }
        $data = [];
        $data['entity'] = $entity;

        return $this->render('OjsJournalBundle:Suggestion:institute_detail.html.twig', $data);
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
        return $this->redirectToRoute('journal_suggestion');
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
        return $this->redirectToRoute('institute_suggestion');
    }


}
