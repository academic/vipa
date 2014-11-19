<?php

namespace Ojs\JournalBundle\Controller\ArticleSubmission;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticleSubmissionStep2Controller extends Controller
{

    public function addAuthorsAction(Request $request, $articleId)
    {
        $authorsData = json_decode($request->request->get('authorsData'));
        if (empty($authorsData)) {
            return new \Symfony\Component\HttpFoundation\Response('', 400);
        }
        $dm = $this->get('doctrine_mongodb')->getManager(); 
        $articleSubmission = $dm->getRepository('OjsJournalBundle:ArticleSubmission')
                ->findByArticleId($articleId);
        $articleSubmission->setAuthors($authorsData);
        $dm->persist($articleSubmission);
        $dm->flush();
        return new JsonResponse($articleSubmission->getId());
    }

}
