<?php

namespace Ojs\JournalBundle\Controller\ArticleSubmission;

use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Document\ArticleSubmissionProgress;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ArticleSubmissionStep3Controller
 * @package Ojs\JournalBundle\Controller\ArticleSubmission
 */
class ArticleSubmissionStep3Controller extends Controller
{
    /**
     * @param  Request               $request
     * @return JsonResponse|Response
     */
    public function addCitationsAction(Request $request)
    {
        $citeData = json_decode($request->request->get('citeData'));
        $submissionId = $request->get("submissionId");
        if (empty($citeData)) {
            return new Response('Missing argument', 400);
        }
        $dm = $this->get('doctrine_mongodb')->getManager();
        /** @var ArticleSubmissionProgress $articleSubmission */
        $articleSubmission = $dm->getRepository('OjsJournalBundle:ArticleSubmissionProgress')
                ->find($submissionId);
        for ($i = 0; $i < count($citeData); $i++) {
            if (strlen($citeData[$i]->raw) < 1) {
                unset($citeData[$i]);
            }
        }
        $articleSubmission->setCitations($citeData);
        $dm->persist($articleSubmission);
        $dm->flush();

        return new JsonResponse($articleSubmission->getId());
    }
}
