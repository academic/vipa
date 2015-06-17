<?php

namespace Ojs\AdminBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Ojs\Common\Params\ArticleEventLogParams;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function dashboardCheckAction()
    {
        if ($this->isGranted('VIEW', new Journal())) {
            return $this->redirect($this->generateUrl('ojs_admin_dashboard'));
        } elseif ($this->isGranted('VIEW', $this->get('ojs.journal_service')->getSelectedJournal())) {
            return $this->redirect($this->generateUrl('dashboard_editor'));
        } else {
            return $this->redirect($this->generateUrl('ojs_user_index'));
        }
    }

    /**
     * @return RedirectResponse|Response
     */
    public function dashboardAction()
    {
        if ($this->isGranted('VIEW', new Journal())) {
            return $this->render('OjsAdminBundle:Admin:dashboard.html.twig');
        } else {
            return $this->redirect($this->generateUrl('dashboard_editor'));
        }
    }

    /**
     * @return RedirectResponse|Response
     */
    public function statsAction()
    {
        if ($this->isGranted('VIEW', new Journal())) {
            return $this->render(
                'OjsAdminBundle:Admin:stats.html.twig',
                [
                    'stats' => $this->getStats(),
                ]
            );
        } else {
            return $this->redirect($this->generateUrl('dashboard_editor'));
        }
    }

    /**
     * Returns general stats;
     * - Journal user count
     * - Journal article count
     * - Journal issue count
     * - Last 30 day most viewed article entity
     * - Last 30 day most viewed article view count
     * - Last 30 day most downloaded article entity
     * - Last 30 day most downloaded article download count
     * @return mixed
     */
    private function getStats()
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $stats['userCount'] = $em
            ->createQuery('SELECT COUNT(a) FROM OjsUserBundle:UserJournalRole a WHERE a.journalId = :journal_id')
            ->setParameter('journal_id', $journal->getId())
            ->getSingleScalarResult();
        $stats['articleCount'] = $em
            ->createQuery('SELECT COUNT(a) FROM OjsJournalBundle:Article a WHERE a.journalId = :journal_id')
            ->setParameter('journal_id', $journal->getId())
            ->getSingleScalarResult();
        $stats['issueCount'] = $em
            ->createQuery('SELECT COUNT(a) FROM OjsJournalBundle:Issue a WHERE a.journalId = :journal_id')
            ->setParameter('journal_id', $journal->getId())
            ->getSingleScalarResult();

        /**
         * get most common value from article_event_log
         * for query {@link http://stackoverflow.com/a/7693627/2438520}
         * @todo query result can set session or memcache for more performance.
         * @todo SQL code will be moved
         */
        $now = new \DateTime('-30 days');
        $last30Day = $now->format("Y-m-d H:i:s");
        $mostViewedArticleLog = $em
            ->createQuery(
                'SELECT a.articleId,COUNT(a) AS viewCount FROM OjsJournalBundle:ArticleEventLog a WHERE a.eventInfo = :event_info AND a.eventDate > :date GROUP BY a.articleId ORDER BY viewCount DESC'
            )
            ->setParameter('event_info', ArticleEventLogParams::$ARTICLE_VIEW)
            ->setParameter('date', $last30Day)
            ->setMaxResults(1)
            ->getResult();
        if (isset($mostViewedArticleLog[0])) {
            $stats['article']['mostViewedArticle'] = $em
                ->getRepository('OjsJournalBundle:Article')
                ->find($mostViewedArticleLog[0]['articleId']);
            $stats['article']['mostViewedArticleCount'] = $mostViewedArticleLog[0]['viewCount'];
        }

        $mostDownloadedArticleLog = $em
            ->createQuery(
                'SELECT a.articleId,COUNT(a) AS downloadCount FROM OjsJournalBundle:ArticleEventLog a WHERE a.eventInfo = :event_info AND a.eventDate > :date GROUP BY a.articleId ORDER BY downloadCount DESC'
            )
            ->setParameter('event_info', ArticleEventLogParams::$ARTICLE_DOWNLOAD)
            ->setParameter('date', $last30Day)
            ->setMaxResults(1)
            ->getResult();
        if (isset($mostDownloadedArticleLog[0])) {
            $stats['article']['mostDownloadedArticle'] = $em
                ->getRepository('OjsJournalBundle:Article')
                ->find($mostDownloadedArticleLog[0]['articleId']);
            $stats['article']['mostDownloadedArticleCount'] = $mostDownloadedArticleLog[0]['downloadCount'];
        }

        return $stats;
    }
}