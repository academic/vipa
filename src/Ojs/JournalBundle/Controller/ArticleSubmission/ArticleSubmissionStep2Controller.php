<?php

namespace Ojs\JournalBundle\Controller\ArticleSubmission;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ArticleSubmissionStep2Controller
 * @package Ojs\JournalBundle\Controller\ArticleSubmission
 */
class ArticleSubmissionStep2Controller extends Controller
{
    /**
     * @param  Request               $request
     * @return JsonResponse|Response
     */
    public function addAuthorsAction(Request $request)
    {
        $authorsData = json_decode($request->request->get('authorsData'));
        $submissionId = $request->get("submissionId");
        if (empty($authorsData)) {
            return new Response('Missing argument', 400);
        }
        for ($i = 0; $i < count($authorsData); $i++) {
            if (empty($authorsData[$i]->firstName)) {
                unset($authorsData[$i]);
            }
        }
        $dm = $this->get('doctrine_mongodb')->getManager();
        $articleSubmission = $dm->getRepository('OjsJournalBundle:ArticleSubmissionProgress')
                ->find($submissionId);
        if (!$articleSubmission) {
            throw $this->createNotFoundException('No submission found');
        }
        if (!$this->isGranted('EDIT', $articleSubmission)) {
            throw $this->createAccessDeniedException("Access Denied");
        }
        $articleSubmission->setAuthors($authorsData);
        $dm->persist($articleSubmission);
        $dm->flush();

        return new JsonResponse($articleSubmission->getId());
    }
}
