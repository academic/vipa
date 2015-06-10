<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EditorController extends Controller
{

    /**
     *
     * Dashboard for editors
     * @return Response
     */
    public function dashboardAction()
    {
        return $this->render(
            'OjsJournalBundle:Editor:dashboard.html.twig',
            [
                'stats' => $this->getStats(),
            ]
        );
    }

    /**
     * @return Response
     * @throws HttpException
     */
    public function myJournalsAction()
    {
        $user_id = $this->getUser()->getId();
        if (!$user_id) {
            throw new HttpException(403, 'There is a problem while getting user information. Access denied');
        }
        $entities = $this->getDoctrine()->getRepository('OjsUserBundle:UserJournalRole')
            ->userJournalsWithRoles($user_id);

        return $this->render(
            'OjsJournalBundle:Editor:myjournals.html.twig',
            array(
                'entities' => $entities,
            )
        );
    }

    /**
     * @param $id
     * @return Response
     */
    public function showJournalAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($id);

        return $this->render('OjsJournalBundle:Journal:role_based/show_editor.html.twig', array('entity' => $journal));
    }

    /**
     * Returns general stats;
     * - Journal user count
     * - Journal article count
     * - Journal issue count
     * - Last 30 day most viewed article
     * - Last 30 day most viewed article view count
     * - Last 30 day most downloaded article
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
         */
        $now = new \DateTime('-30 days');
        $last30Day = $now->format("Y-m-d H:i:s");
        $mostViewedArticleLog = $em
            ->createQuery(
                'SELECT a.articleId,COUNT(a) AS viewCount FROM OjsJournalBundle:ArticleEventLog a WHERE a.eventInfo = :event_info AND a.eventDate > :date GROUP BY a.articleId ORDER BY viewCount DESC'
            )
            ->setParameter('event_info', \Ojs\Common\Params\ArticleEventLogParams::$ARTICLE_VIEW)
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
            ->setParameter('event_info', \Ojs\Common\Params\ArticleEventLogParams::$ARTICLE_DOWNLOAD)
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
