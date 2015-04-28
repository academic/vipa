<?php

namespace Ojs\ReportBundle\Controller;

use Ojs\JournalBundle\Entity\Article;
use Ojs\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AnalyticsReportController extends Controller
{

    public function indexAction()
    {
        $data = [];
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $object_view = $dm->getRepository('OjsAnalyticsBundle:ObjectViews');
        /** @var User $user */
        $user = $this->getUser();

        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        $journal_stats = [];
        $journal_stats['info'] = $journal;
        $journal_stats['stats'] = $object_view->findBy(['entity' => 'journal', 'objectId' => $journal->getId()]);
        $articles = $journal->getArticles()->toArray();

        $data['stats'] = [];
        $data['stats']['journal'] = $journal_stats;

        $article_stats = [];
        foreach ($articles as $article) {
            /** @var Article $_a */
            $_a = [
                'info' => $article,
                'stats' => $object_view->findBy(['entity' => 'article', 'objectId' => $article->getId()]),
            ];
            $article_stats[] = $_a;
        }
        $data['stats']['article'] = $article_stats;

        return $this->render('OjsReportBundle:analytics:index.html.twig', $data);
    }

}
