<?php

namespace Ojs\SiteBundle\Controller;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\NoResultException;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RedirectController extends Controller
{

    /**
     * /redirect/{type}/id  will redirect to related page after checking user login status
     * @param  string $type
     * @param  string|integer $id
     * @return RedirectResponse
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
    }

    /**
     *
     * @param  Registry $doctrine
     * @param  string|integer $id
     * @return RedirectResponse
     * @throws NoResultException
     */
    private function redirectArticle($doctrine, $id)
    {
        $article = $doctrine->getRepository('OjsJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($article);

        return $this->redirect(
            $this->generateUrl(
                'ojs_article_withoutIssue_page_without_publisher',
                array(
                    'slug' => $article->getJournal()->getSlug(),
                    'article_id' => $article->getId()
                )
            )
        );
    }

    /**
     *
     * @param  Registry $doctrine
     * @param  string|integer $id
     * @return RedirectResponse
     * @throws NoResultException
     */
    private function redirectJournal($doctrine, $id)
    {
        $journal = $doctrine->getRepository('OjsJournalBundle:Journal')->find($id);
        $this->throw404IfNotFound($journal);

        return $this->redirect($this->get('ojs.journal_service')->generateUrl($journal));
    }
}
