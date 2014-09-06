<?php

namespace Ojstr\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SiteController extends Controller {

    /**
     * Global index page
     * @return type
     */
    public function indexAction() {
        /* @var $journalDomain \Ojstr\Common\Model\JournalDomain */
        $journalDomain = $this->container->get('journal_domain');
        $currentJournal = $journalDomain->getCurrentJournal();

        if ($currentJournal) {
            return $this->render('OjstrJournalBundle:Journal:public_index.html.twig', array('entity' => $currentJournal));
        }
        // anything else is anonym main page
        return $this->render('OjstrSiteBundle::Site/anonymous_index.html.twig');
    }

    public function userIndexAction() {
        return $this->render('OjstrManagerBundle:User:userwelcome.html.twig');
    }

}
