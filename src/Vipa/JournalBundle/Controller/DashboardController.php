<?php

namespace Vipa\JournalBundle\Controller;

use Vipa\CoreBundle\Controller\VipaController;

class DashboardController extends VipaController
{
    public function indexAction()
    {
        return $this->render('VipaJournalBundle:Dashboard:dashboard.html.twig', [
            'articlePieStats' => $this->getArticlePieStats()
        ]);
    }

    /**
     * @return array
     */
    private function getArticlePieStats()
    {
        $em = $this->getDoctrine()->getManager();
        $userArticles = $em->getRepository('VipaJournalBundle:Article')->findBy([
           'submitterUser' => $this->getUser()
        ]);
        $stats = [];
        foreach($userArticles as $article){
            $articleStatus = $article->getStatusText();
            if(isset($stats[$articleStatus])){
                $stats[$articleStatus]++;
            }else{
                $stats[$articleStatus] = 1;
            }
        }
        return $stats;
    }
}
