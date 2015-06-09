<?php

namespace Ojs\JournalBundle\Controller\ArticleSubmission;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ArticleSubmissionStep4Controller
 * @package Ojs\JournalBundle\Controller\ArticleSubmission
 */
class ArticleSubmissionStep4Controller extends Controller
{
    /**
     * @param  Request               $request
     * @return JsonResponse|Response
     */
    public function addFilesAction(Request $request)
    {
        $filesData = json_decode($request->request->get('filesData'));
        $submissionId = $request->get("submissionId");
        $dm = $this->get('doctrine_mongodb')->getManager();
        $articleSubmission = $dm->getRepository('OjsJournalBundle:ArticleSubmissionProgress')
                ->find($submissionId);
        if (!$articleSubmission) {
            throw $this->createNotFoundException('No submission found');
        }
        if (!$this->isGranted('EDIT', $articleSubmission)) {
            throw $this->createAccessDeniedException("Access Denied");
        }
        if (empty($filesData) || !$submissionId || !$articleSubmission) {
            return new Response('Missing argument', 400);
        }

        for ($i = 0; $i < count($filesData); $i++) {
            if (strlen($filesData[$i]->article_file) < 1) {
                unset($filesData[$i]);
            }
        }
        $articleSubmission->setFiles($filesData);
        $dm->persist($articleSubmission);
        $dm->flush();

        return new JsonResponse(array('redirect' => $this->generateUrl('article_submission_preview', array('submissionId' => $submissionId))));
    }
}
