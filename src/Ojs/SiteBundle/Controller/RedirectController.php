<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;

class RedirectController extends Controller
{

    /**
     * /redirect/{type}/id  will redirect to related page after checking user login status
     * @param string $type
     * @param string|integer $id
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     * @throws NoResultException
     */
    public function redirectAction($type, $id)
    {
        $doctrine = $this->getDoctrine(); 
        switch ($type) {
            case 'article':
                return $this->redirectArticle($doctrine, $id);
            case 'journal':
                return $this->redirectJournal($doctrine, $id);
            default:
                throw $this->createNotFoundException();
        }
        return $this->redirect($this->generateUrl());
    }

    /**
     * 
     * @param Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     * @param string|integer $id
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     * @throws NoResultException
     */
    private function redirectArticle($doctrine, $id)
    {
        $article = $doctrine->getRepository('OjsJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($article);
        return $this->redirect($this->generateUrl('ojs_article_page', array(
                            'slug' => $article->getJournal()->getSlug(),
                            'article_slug' => $article->getSlug(),
                            'institution' => $article->getJournal()->getInstitution()->getSlug())
        ));
    }

    /**
     * 
     * @param Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     * @param string|integer $id
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     * @throws NoResultException
     */
    private function redirectJournal($doctrine, $id)
    {
        $journal = $doctrine->getRepository('OjsJournalBundle:Journal')->find($id);
        $this->throw404IfNotFound($journal);
        return $this->redirect($this->generateUrl('ojs_journal_index', array(
                            'slug' => $journal->getSlug(),
                            'institution' => $journal->getInstitution()->getSlug())
        ));
    }

}
