<?php

namespace Ojs\JournalBundle\Controller\ArticleSubmission;

use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Article;
//use Ojs\JournalBundle\Form\ArticleType;
//use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Ojs\JournalBundle\Document\ArticleSubmissionProgress;

/**
 * Article submission step controller
 */
class ArticleSubmissionStep1Controller extends Controller
{

    /**
     * submit new article - step1 - get article base data without author info.
     *
     */
    public function addArticleAction(Request $request, $locale)
    {
        $articleData = $request->request->all();
        $articleData['translations'] = isset($articleData['translations']) ?
                json_decode($articleData['translations'], true) :
                false;
        $article = $this->addArticleMain($request, $locale);
        if (!$article) {
            return new JsonResponse('error');
        }
        if ($articleData['translations']) {
            foreach ($articleData['translations'] as $params) {
                $this->addArticleTranslation($request, $params['data'], $params['data']['locale'], $article);
            }
        }
        $dm = $this->get('doctrine_mongodb')->getManager(); 
        $articleSubmission = new ArticleSubmissionProgress();
        $articleSubmission->setArticleId($article->getId());
        $articleSubmission->setUserId($this->getUser()->getId());
        $articleSubmission->setStartedDate(new \DateTime());
        $articleSubmission->setLastResumeDate(new \DateTime());
        $dm ->persist($articleSubmission);
        $dm->flush();
        
        return new JsonResponse(array(
            'submissionId'=>$articleSubmission->getId(),
            'id' => $article->getId(),
            'locale' => $locale));
    }

    /**
     * Add Article data for main language
     * @param  \Symfony\Component\HttpFoundation\Request      $request
     * @param  string                                         $locale
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    private function addArticleMain(Request $request, $locale)
    {
        $em = $this->getDoctrine()->getManager();
        $article = new Article();
        $article->setStatus(-1); // Not submitted / see Ojs/Common/Params/CommonParams.php
        $article->setTitle($request->get('title'));
        $article->setSubtitle($request->get('subtitle'));
        $article->setTitleTransliterated($request->get('titleTransliterated'));
        $article->setKeywords($request->get('keywords'));
        $article->setSubjects($request->get('subjects'));
        $article->setAbstract($request->get('abstract'));
        $article->setJournal($em->getRepository('OjsJournalBundle:Journal')->find($request->get('journalId')));
        $article->setPrimaryLanguage($request->get('primaryLanguage'));
        $article->setTranslatableLocale($locale);
        $em->persist($article);
        $em->flush();
        $request->getSession()->set('submission_article', $article);

        return $article;
    }

    /**
     * Update Article data for other languages
     * @param  array                                          $data
     * @param  string                                         $locale
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    private function addArticleTranslation($request, $data, $locale, $article)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $article->setTitle($data['title']);
        $article->setTitleTransliterated($data['titleTransliterated']);
        $article->setSubtitle($data['subtitle']);
        $article->setKeywords($data['keywords']);
        $article->setSubjects($data['subjects']);
        $article->setAbstract($data['abstract']);
        $article->setTranslatableLocale($locale);
        $em->persist($article);
        $em->flush();
        $session->set('submission_article_' . $locale, $article);

        return $article->getId();
    }

}
