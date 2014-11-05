<?php

namespace Ojs\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SiteController extends Controller {
    

    /**
     * Global index page
     * @return type
     */
    public function indexAction() {
        /* @var $journalDomain \Ojs\Common\Model\JournalDomain */
        $journalDomain = $this->container->get('journal_domain');
        $em = $this->getDoctrine()->getManager();
        $journals = $em->getRepository('OjstrJournalBundle:Journal')->findAll();
        
        $data['entity'] = $journalDomain->getCurrentJournal();
        $data['page'] = 'index';
        $data["journals"] = $journals;
        
        if ($data['entity']) {
            return $this->render('OjstrJournalBundle:Journal:public_index.html.twig',$data);
        }
        // anything else is anonym main page
        return $this->render('OjsSiteBundle::Site/anonymous_index.html.twig',$data);
    }

    public function userIndexAction() {
        $data['page'] = 'user';
        return $this->render('OjstrManagerBundle:User:userwelcome.html.twig',$data);
    }

    public function browseIndexAction() {
        $data['page'] = 'browse';
        return $this->render('OjsSiteBundle::Site/browse_index.html.twig',$data);
    }

    public function organizationsIndexAction() {

        $data['page'] = 'organizations';
        return $this->render('OjsSiteBundle::Site/organizations_index.html.twig',$data);
    }

    public function categoriesIndexAction() {
        $data['page'] = 'categories';
        return $this->render('OjsSiteBundle::Site/categories_index.html.twig',$data);
    }

    public function topicsIndexAction() {
        $data['page'] = 'topics';
        return $this->render('OjsSiteBundle::Site/topics_index.html.twig',$data);
    }

    public function profileIndexAction() {
        $data['page'] = 'profile';
        return $this->render('OjsSiteBundle::Site/profile_index.html.twig',$data);
    }

    public function staticPagesAction($page = 'static') {
        $data['page'] = $page;
        return $this->render('OjsSiteBundle:Site:static/tos.html.twig',$data);
    }

    public function journalsIndexAction() {
        $data['page'] = 'journals';
        return $this->render('OjsSiteBundle::Site/journals_index.html.twig',$data);
    }

    public function journalIndexAction() {
        $data['page'] = 'journal';
        return $this->render('OjsSiteBundle::Site/journal_index.html.twig',$data);
    }

    public function articlesIndexAction() {
        $data['page'] = 'articles';
        return $this->render('OjsSiteBundle::Site/articles_index.html.twig',$data);
    }

    public function archiveIndexAction() {
        $data['page'] = 'archive';
        return $this->render('OjsSiteBundle::Site/archive_index.html.twig',$data);
    }

}
