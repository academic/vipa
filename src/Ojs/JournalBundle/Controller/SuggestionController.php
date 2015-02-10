<?php

namespace Ojs\JournalBundle\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Form\InstitutionType;

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
        $entities = $dm->getRepository('OjsJournalBundle:InstituteSuggestion')->findBy(['merged'=>null]);
        return $this->render('OjsJournalBundle:Suggestion:institute.html.twig', array(
            'entities' => $entities,
        ));
    }



}
