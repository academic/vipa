<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController;

class DashboardController extends OjsController
{
    public function indexAction()
    {
        return $this->render('OjsJournalBundle:Dashboard:dashboard.html.twig', [
            'articlePieStats' => $this->getArticlePieStats()
        ]);
    }

    /**
     * @return array
     */
    private function getArticlePieStats()
    {
        $em = $this->getDoctrine()->getManager();
        $userArticles = $em->getRepository('OjsJournalBundle:Article')->findBy([
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
