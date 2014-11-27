<?php

namespace Ojs\JournalBundle\Controller\ArticleSubmission;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticleSubmissionStep3Controller extends Controller
{

    public function addCitationsAction(Request $request)
    {
        $citeData = json_decode($request->request->get('citeData'));
        $submissionId = $request->get("submissionId");
        if (empty($citeData)) {
            return new \Symfony\Component\HttpFoundation\Response('Missing argument', 400);
        }
        $dm = $this->get('doctrine_mongodb')->getManager();
        $articleSubmission = $dm->getRepository('OjsJournalBundle:ArticleSubmissionProgress')
                ->find($submissionId);
        for ($i = 0; $i < count($citeData); $i++) {
            if (strlen($citeData[$i]->raw)<1) {
                unset($citeData[$i]);
            }
        }
        $articleSubmission->setCitations($citeData);
        error_log(print_r($citeData,true));
        $dm->persist($articleSubmission);
        $dm->flush();
        return new JsonResponse($articleSubmission->getId());
    }

}
