<?php

namespace Ojstr\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Ojstr\Common\Controller\OjsController as Controller;
use Ojstr\JournalBundle\Entity\Article;
//use Ojstr\JournalBundle\Form\ArticleType;
//use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Article submission step controller
 */
class ArticleSubmissionStepController extends Controller {

    /**
     * submit new article - step1 - get article base data without author info.
     *
     */
    public function step1Action(Request $request, $locale) {
        if ($request->get('articleId')) {
            return $this->step1Extra($request, $locale);
        }
        $em = $this->getDoctrine()->getManager();
        $article = new Article();
        $article->setStatus(-1); // Not submitted / see Ojstr/Common/Params/CommonParams.php
        $article->setTitle($request->get('title'));
        $article->setSubtitle($request->get('subtitle'));
        $article->setTitleTransliterated($request->get('titleTransliterated'));
        $article->setKeywords($request->get('keywords'));
        $article->setSubjects($request->get('subjects'));
        $article->setAbstract($request->get('abstract'));
        $article->setJournal($em->getRepository('OjstrJournalBundle:Journal')->find($request->get('journalId')));
        $article->setPrimaryLanguage($request->get('primaryLanguage'));
        $article->setTranslatableLocale($locale);
        $em->persist($article);
        $em->flush();
        return new JsonResponse(array('id' => $article->getId()));
    }

    /**
     * Update Article data for other languages
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $locale
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function step1Extra(Request $request, $locale) {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('OjstrJournalBundle:Article')->find($request->get('articleId'));
        $article->setTitle($request->get('title'));
        $article->setTitleTransliterated($request->get('titleTransliterated'));
        $article->setSubtitle($request->get('subtitle'));
        $article->setKeywords($request->get('keywords'));
        $article->setSubjects($request->get('subjects'));
        $article->setAbstract($request->get('abstract'));
        $article->setTranslatableLocale($locale);
        $em->persist($article);
        $em->flush();
        return new JsonResponse(array('id' => $request->get('articleId'), 'locale' => $locale));
    }

    /**
     * @todo
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function step2Action(Request $request) {
        $em = $this->getDoctrine()->getManager();
    }

    /**
     * @todo
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function step3Action(Request $request) {
        $em = $this->getDoctrine()->getManager();
    }

}
