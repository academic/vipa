<?php

namespace Ojs\JournalBundle\Controller\ArticleSubmission;

use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Ojs\JournalBundle\Document\ArticleSubmissionProgress;

/**
 * Article submission step controller
 * Class ArticleSubmissionStep1Controller
 * @package Ojs\JournalBundle\Controller\ArticleSubmission
 */
class ArticleSubmissionStep1Controller extends Controller
{

    /**
     * submit new article - step1 - get article base data without author info.
     * @param  Request      $request
     * @param $locale
     * @return JsonResponse
     */
    public function addArticleAction(Request $request, $locale)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $articleData = $request->request->all();
        $articleData['translations'] = isset($articleData['translations']) ?
                json_decode($articleData['translations'], true) :
                false;
        $languages = array();
        $articleSubmissionData = array();
        $article = $this->generateArticleArray($articleData, $locale);
        $articleSubmissionData[$locale] = $article;
        if ($articleData['translations']) {
            foreach ($articleData['translations'] as $params) {
                $languages[] = $params['data']['locale'];
                $articleSubmissionData[$params['data']['locale']] = $this->generateArticleArray($params['data'], $params['data']['locale'], $article);
            }
        }
        // save submission data to mongodb for resume action
        if (!$articleData["submissionId"]) {
            $articleSubmission = new ArticleSubmissionProgress();
        } else {
            $articleSubmission = $dm->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->find($articleData["submissionId"]);
        }
        $articleSubmission->setArticleData($articleSubmissionData);
        $articleSubmission->setUserId($this->getUser()->getId());
        $articleSubmission->setJournalId($articleData["journalId"]);
        $articleSubmission->setPrimaryLanguage($articleData["primaryLanguage"]);
        $articleSubmission->setStartedDate(new \DateTime());
        $articleSubmission->setLastResumeDate(new \DateTime());
        $articleSubmission->setLanguages($languages);
        $articleSubmission->setSection($articleData['section']);
        $dm->persist($articleSubmission);
        $dm->flush();

        return new JsonResponse(array(
            'submissionId' => $articleSubmission->getId(),
            'locale' => $locale, ));
    }

    /**
     * @param $data
     * @param  null  $locale
     * @return mixed
     */
    private function generateArticleArray($data, $locale = null)
    {
        $article['title'] = $data['title'];
        $article['subtitle'] = $data['subtitle'];
        $article['keywords'] = $data['keywords'];
        $article['subjects'] = $data['subjects'];
        $article['abstract'] = $data['abstract'];
        $article['locale'] = $locale;

        return $article;
    }
}
