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

    public function browseIndexAction() {
        return $this->render('OjstrSiteBundle::Site/browse_index.html.twig');
    }

    public function organizationsIndexAction() {
        return $this->render('OjstrSiteBundle::Site/organizations_index.html.twig');
    }

    public function categoriesIndexAction() {
        return $this->render('OjstrSiteBundle::Site/categories_index.html.twig');
    }

    public function topicsIndexAction() {
        return $this->render('OjstrSiteBundle::Site/topics_index.html.twig');
    }

    public function profileIndexAction() {
        return $this->render('OjstrSiteBundle::Site/profile_index.html.twig');
    }
    
    
    

    public function staticPagesAction($page = null) { 
        return $this->render(
                        'OjstrSiteBundle:Site:static/tos.html.twig'
        );
    }

    public function journalIndexAction() {
        return $this->render('OjstrSiteBundle::Site/journal_index.html.twig');
    }

    public function articlesIndexAction() {
        return $this->render('OjstrSiteBundle::Site/articles_index.html.twig');
    }

    public function archiveIndexAction() {
        return $this->render('OjstrSiteBundle::Site/archive_index.html.twig');
    }

}
