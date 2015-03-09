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
        $object_view = $dm->getRepository('OjsAnalyticsBundle:ObjectView');
        /** @var User $user */
        $user = $this->getUser();
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $urj = $em->getRepository('OjsUserBundle:UserJournalRole');
        $journals = $urj->findBy(['user' => $user]);
        $journal_stats = [];
        $articles = [];
        foreach ($journals as $j) {
            $journal = $j->getJournal();
            $_j['info'] = $journal;
            $_j['stats'] = $object_view->findBy(['entity' => 'journal', 'objectId' => $j->getJournalId()]);
            $journal_stats[] = $_j;
            $_a = $journal->getArticles()->toArray();
            $articles = array_merge($articles,$_a);
        }

        $data['stats'] = [];
        $data['stats']['journal'] = $journal_stats;

        $article_stats = [];
        foreach($articles as $article){
            /** @var Article $_a */
            $_a = [
                'info'=>$article,
                'stats'=>$object_view->findBy(['entity'=>'article','objectId'=>$article->getId()]),
            ];
            $article_stats[] = $_a;
        }
        $data['stats']['article'] = $article_stats;

        return $this->render('OjsReportBundle:analytics:index.html.twig', $data);
    }

}
