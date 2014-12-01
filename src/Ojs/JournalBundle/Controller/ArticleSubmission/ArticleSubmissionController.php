<?php

namespace Ojs\JournalBundle\Controller\ArticleSubmission;

use Ojs\Common\Params\ArticleFileParams;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Author;
use \Ojs\JournalBundle\Entity\Citation;
use \Ojs\JournalBundle\Entity\CitationSetting;
use Ojs\JournalBundle\Entity\Journal;
use \Ojs\JournalBundle\Entity\ArticleAuthor;
use Ojs\JournalBundle\Form\ArticleType;
use Ojs\Common\Helper\CommonFormHelper as CommonFormHelper;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Article Submission controller.
 *
 */
class ArticleSubmissionController extends Controller
{

    /**
     * Lists all new Article submissions entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $user = $this->getUser();

        $submissions = $em->getRepository('OjsJournalBundle:Article')->findBy(array('status' => 0, 'submitterId' => $user->getId()));
        $resumableSubmissions = $dm->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->
                findBy(array('user_id'=>$user->getId(), 'sumitted'=>false));
        return $this->render('OjsJournalBundle:ArticleSubmission:index.html.twig', array(
                    'submissions' => $submissions,
                    'resumableSubmissions' => $resumableSubmissions
        ));
    }

    /**
     * Lists all new Article submissions entities.
     *
     */
    public function indexAllAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsJournalBundle:Article')->findBy(array('status' => 0));

        return $this->render('OjsJournalBundle:ArticleSubmission:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Displays a form to create a new Article entity.
     * 
     * @param int $submissionId 
     */
    public function newAction()
    {
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        if (!$journal) {
            return $this->render('::mustselectjournal.html.twig');
        }
        $entity = new Article();
        return $this->render('OjsJournalBundle:ArticleSubmission:new.html.twig', array(
                    'articleId' => NULL,
                    'entity' => $entity,
                    'journal' => $journal,
                    'submissionData' => NULL,
                    'fileTypes' => ArticleFileParams::$FILE_TYPES,
                    'citationTypes' => $this->container->getParameter('citation_types')
        ));
    }

    public function resumeAction($submissionId)
    {
        $em = $this->getDoctrine()->getManager();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $articleSubmission = $dm->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->find($submissionId);
        if ($articleSubmission->getUserId() !== $this->getUser()->getId()) {
            throw $this->createAccessDeniedException("Access denied!");
        }
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($articleSubmission->getJournalId());
        return $this->render('OjsJournalBundle:ArticleSubmission:new.html.twig', array(
                    'submissionId' => $articleSubmission->getId(),
                    'submissionData' => $articleSubmission,
                    'journal' => $journal,
                    'fileTypes' => ArticleFileParams::$FILE_TYPES,
                    'citations' => $articleSubmission->getCitations(),
                    'citationTypes' => $this->container->getParameter('citation_types')
        ));
    }

    public function previewAction($submissionId)
    {
        $em = $this->getDoctrine()->getManager();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $articleSubmission = $dm->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->find($submissionId);
        if ($articleSubmission->getUserId() !== $this->getUser()->getId()) {
            throw $this->createAccessDeniedException("Access denied!");
        }
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($articleSubmission->getJournalId());
        return $this->render('OjsJournalBundle:ArticleSubmission:preview.html.twig', array(
                    'submissionId' => $articleSubmission->getId(),
                    'submissionData' => $articleSubmission,
                    'journal' => $journal,
        ));
    }

    public function finishAction(Request $request)
    {
        $submissionId = $request->get('submissionId');
        if (!$submissionId) {
            throw $this->createNotFoundException("Submission not found");
        }
        $em = $this->getDoctrine()->getManager();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $articleSubmission = $dm->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->find($submissionId);
        if ($articleSubmission->getUserId() !== $this->getUser()->getId()) {
            throw $this->createAccessDeniedException("Access denied!");
        }
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($articleSubmission->getJournalId());

        if (!$journal) {
            throw $this->createNotFoundException('Journal not found');
        }
        $translationRepository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        /* article submission data will be moved from mongdb to mysql */
        $articleData = $articleSubmission->getArticleData();
        $articlePrimaryData = $articleData[$articleSubmission->getPrimaryLanguage()];

        // article primary data
        $article = new Article();
        $article->setPrimaryLanguage($articleSubmission->getPrimaryLanguage());

        $article->setJournal($journal);
        $article->setTitle($articlePrimaryData['title']);
        $article->setAbstract($articlePrimaryData['abstract']);
        $article->setKeywords($articlePrimaryData['keywords']);
        $article->setSubjects($articlePrimaryData['subjects']);
        $article->setSubtitle($articlePrimaryData['title']);
        $article->setSubmitterId($this->getUser()->getId());
        $article->setStatus(0);
        // article data for other languages if provided
        unset($articleData[$articleSubmission->getPrimaryLanguage()]);
        foreach ($articleData as $locale => $data) {
            $translationRepository->translate($article, 'title', $locale, $data['title'])
                    ->translate($article, 'abstract', $locale, $data['abstract'])
                    ->translate($article, 'keywords', $locale, $data['keywords'])
                    ->translate($article, 'subjects', $locale, $data['subjects'])
                    ->translate($article, 'title', $locale, $data['title']);
        }
        $em->persist($article);
        $em->flush();

        // author data
        foreach ($articleSubmission->getAuthors() as $authorData) {
            $author = new Author();
            $author->setEmail($authorData['email']);
            $author->setTitle($authorData['title']);
            $author->setInitials($authorData['initials']);
            $author->setFirstName($authorData['firstName']);
            $author->setLastName($authorData['lastName']);
            $author->setMiddleName($authorData['middleName']);
            $author->setSummary($authorData['summary']);
            $em->persist($author);
            $em->flush();
            $articleAuthor = new ArticleAuthor();
            $articleAuthor->setArticle($article);
            $articleAuthor->setAuthorOrder($authorData['order']);
            $articleAuthor->setAuthor($author);
            $em->persist($articleAuthor);
            $em->flush();
        }

        // citation data
        foreach ($articleSubmission->getCitations() as $citationData) {
            $citation = new Citation();
            $citation->setRaw($citationData['raw']);
            $citation->setType($citationData['type']);
            $citation->setOrderNum($citationData['orderNum']);
            $em->persist($citation);
            $em->flush();
            unset($citationData['raw']);
            unset($citationData['type']);
            unset($citationData['orderNum']);
            /// add as citation setting other 
            foreach ($citationData as $setting => $value) {
                $citationSetting = new CitationSetting();
                $citationSetting->setSetting($setting);
                $citationSetting->setValue($value);
                $citationSetting->setCitation($citation);
                $em->persist($citationSetting);
                $em->flush($citationSetting);
            }
        }

        // file data
        foreach ($articleSubmission->getFiles() as $fileData) {
            $file = new \Ojs\JournalBundle\Entity\File();
            $file->setPath($fileData['article_file']);
            $file->setName($fileData['article_file']);
            $file->setMimeType($fileData['article_file_mime_type']);
            $file->setSize($fileData['article_file_size']);

            // @todo add get mime type and name
            $em->persist($file);
            $em->flush();
            $articleFile = new \Ojs\JournalBundle\Entity\ArticleFile();
            $articleFile->setArticle($article);
            $articleFile->setFile($file);


            if ($fileData['type'] != 0) {
                $articleFile->setTitle($fileData['title']);
                $articleFile->setDescription($fileData['desc']);
                $articleFile->setKeywords($fileData['keywords']);
                $articleFile->setLangCode($fileData['lang']);
            } else {
                $articleFile->setLangCode($articleSubmission->getPrimaryLanguage());
            }
            $articleFile->setVersion(1);
            $articleFile->setType($fileData['type']); // @see ArticleFileParams::$FILE_TYPES
            // article full text 

            $em->persist($articleFile);
            $em->flush();
        }

        $this->get('session')->getFlashBag()->add('info', 'Your submission is successfully sent.');

        // @todo give ref. link or code or directives to author 
        $articleSubmission->setSubmitted(1);
        $dm->persist($articleSubmission);
        $dm->flush();
        return $this->redirect($this->generateUrl('article_submissions_me'));
    }

}
