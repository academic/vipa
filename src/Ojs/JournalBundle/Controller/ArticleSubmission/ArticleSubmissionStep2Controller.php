<?php

namespace Ojs\JournalBundle\Controller\ArticleSubmission;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticleSubmissionStep2Controller extends Controller
{

    public function addAuthorsAction(Request $request)
    {
        $authorsData = json_decode($request->request->get('authorsData'));
        $submissionId = $request->get("submissionId");
        if (empty($authorsData)) {
            return new \Symfony\Component\HttpFoundation\Response('', 400);
        }
        $dm = $this->get('doctrine_mongodb')->getManager();
        $articleSubmission = $dm->getRepository('OjsJournalBundle:ArticleSubmissionProgress')
                ->find($submissionId);
        $articleSubmission->setAuthors($authorsData);
        $dm->persist($articleSubmission);
        $dm->flush();
        return new JsonResponse($articleSubmission->getId());
    }

}
