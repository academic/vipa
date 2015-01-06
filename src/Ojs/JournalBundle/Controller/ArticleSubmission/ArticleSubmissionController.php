<?php

namespace Ojs\JournalBundle\Controller\ArticleSubmission;

use Ojs\Common\Params\ArticleFileParams;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Author;
use \Ojs\JournalBundle\Entity\Citation;
use \Ojs\JournalBundle\Entity\CitationSetting;
use \Ojs\JournalBundle\Entity\ArticleAuthor;
use \Ojs\WorkflowBundle\Document\ArticleReviewStep;

/**
 * Article Submission controller.
 *
 */
class ArticleSubmissionController extends Controller
{

    /**
     * Lists all new Article submissions entities.
     */
    public function indexAction($all = false)
    {
        $em = $this->getDoctrine()->getManager();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $user = $this->getUser();
        if ($all) {
            // all submissions and submission drafts
            $submissions = $em->getRepository('OjsJournalBundle:Article')->findBy(array('status' => 0));
            $drafts = $dm->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->
                    findBy(array('submitted' => false));
        } else {
            // just me
            $submissions = $em->getRepository('OjsJournalBundle:Article')->findBy(array('status' => 0, 'submitterId' => $user->getId()));
            $drafts = $dm->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->
                    findBy(array('userId' => $user->getId(), 'submitted' => false));
        }
        return $this->render('OjsJournalBundle:ArticleSubmission:index.html.twig', array(
                    'submissions' => $submissions,
                    'drafts' => $drafts,
                    'all' => $all,
                    'statusColors' => \Ojs\Common\Params\CommonParams::getStatusColors(),
                    'statusTexts' => \Ojs\Common\Params\CommonParams::getStatusTexts()
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

    /**
     * Resume action for an article submission
     * @param string $submissionId
     * @throws 403 Access denied
     */
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

    /**
     * Preview action for an article submission
     * @param string $submissionId
     * @throws 403 Access Denied
     */
    public function previewAction($submissionId)
    {
        $em = $this->getDoctrine()->getManager();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $articleSubmission = $dm->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->find($submissionId);
        if ($articleSubmission->getUserId() !== $this->getUser()->getId()) {
            throw $this->createAccessDeniedException("Access denied!");
        }
        if ($articleSubmission->getSubmitted()) {
            throw $this->createAccessDeniedException("Access denied! This submission has already been submitted.");
        }
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($articleSubmission->getJournalId());
        return $this->render('OjsJournalBundle:ArticleSubmission:preview.html.twig', array(
                    'submissionId' => $articleSubmission->getId(),
                    'submissionData' => $articleSubmission,
                    'journal' => $journal,
        ));
    }

    /**
     * Finish action for an article submission. 
     * This action moves article's data from mongodb to mysql
     * @param Request $request
     * @throws 403 Acces Denied
     */
    public function finishAction(Request $request)
    {
        $submissionId = $request->get('submissionId');
        if (!$submissionId) {
            throw $this->createNotFoundException('There is no submission with this Id.');
        }
        $dm = $this->get('doctrine_mongodb')->getManager();

        $em = $this->get('doctrine')->getManager();
        $articleSubmission = $dm->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->find($submissionId);
        if (!$articleSubmission) {
            throw $this->createNotFoundException('Submission not found.');
        }
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($articleSubmission->getJournalId());
        if (!$journal) {
            throw $this->createNotFoundException('Journal not found');
        }

        $article = $this->saveArticleSubmission($articleSubmission, $journal);

        // get journal's first workflow step
        $firstStep = $this->get('doctrine_mongodb')->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->findOneBy(array('journalid' => $journal->getId(), 'firststep' => true));
        if ($firstStep) {
            $reviewStep = new ArticleReviewStep();
            $reviewStep->setArticleId($article->getId());
            $reviewStep->setSubmitterId($this->getUser()->getId());
            $reviewStep->setStartedDate(new \DateTime());
            $reviewStep->setStatusText($firstStep->getStatus());
            $deadline = new \DateTime();
            $deadline->modify("+" . $firstStep->getMaxdays() . " day");
            $reviewStep->setReviewDeadline($deadline);
            $reviewStep->setRootNode(true);
            $reviewStep->setTo(null);
            $reviewStep->setStepId($firstStep->getId());
            $dm->persist($reviewStep);
            $dm->flush();
        }
        $articleSubmission->setSubmitted(1);
        $dm->persist($articleSubmission);
        $dm->flush();
        return $this->redirect($this->generateUrl('article_submissions_me'));
    }

    /**
     * Saves article submission data from mongodb to mysql
     * Article submission data will be kept on mongodb as archive data
     * @param integer $submissionId 
     * @return 
     */
    private function saveArticleSubmission($articleSubmission, $journal)
    {
        // security check for submission owner and current user
        if ($articleSubmission->getUserId() !== $this->getUser()->getId()) {
            throw $this->createAccessDeniedException("Access denied!");
        }
        /* article submission data will be moved from mongdb to mysql */
        $articleData = $articleSubmission->getArticleData();
        $articlePrimaryData = $articleData[$articleSubmission->getPrimaryLanguage()];
        // article primary data
        $article = $this->saveArticlePrimaryData($articlePrimaryData, $journal, $articleSubmission->getPrimaryLanguage());
        // article data for other languages if provided
        unset($articleData[$articleSubmission->getPrimaryLanguage()]);
        $this->saveArticleTranslations($articleData, $article);
        // add authors data
        $this->saveAuthorsData($articleSubmission->getAuthors(), $article);
        // citation data
        $this->saveCitationData($articleSubmission->getCitations(), $article);
        // file data
        $this->saveArticleFileData($articleSubmission->getFiles(), $article, $articleSubmission->getPrimaryLanguage());

        $this->get('session')->getFlashBag()->add('info', 'Your submission is successfully sent.');
        // @todo give ref. link or code or directives to author 
        return $article;
    }

    /**
     *  
     * @return Article
     */
    private function saveArticlePrimaryData($articlePrimaryData, $journal, $lang)
    {
        $em = $this->get('doctrine')->getManager();

        $article = new Article();
        $article->setPrimaryLanguage($lang);

        $article->setJournal($journal);
        $article->setTitle($articlePrimaryData['title']);
        $article->setAbstract($articlePrimaryData['abstract']);
        $article->setKeywords($articlePrimaryData['keywords']);
        $article->setSubjects($articlePrimaryData['subjects']);
        $article->setSubtitle($articlePrimaryData['title']);
        $article->setSubmitterId($this->getUser()->getId());
        $article->setStatus(0);

        $em->persist($article);
        $em->flush();
        return $article;
    }

    /**
     * 
     * @param array $articleData
     * @param Article $article
     * @return bool
     */
    private function saveArticleTranslations($articleData, $article)
    {
        $em = $this->get('doctrine')->getManager();
        $translationRepository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        foreach ($articleData as $locale => $data) {
            $translationRepository->translate($article, 'title', $locale, $data['title'])
                    ->translate($article, 'abstract', $locale, $data['abstract'])
                    ->translate($article, 'keywords', $locale, $data['keywords'])
                    ->translate($article, 'subjects', $locale, $data['subjects']);
        }
        $em->persist($article);
        return $em->flush();
    }

    /**
     * 
     * @param array $authors
     * @param Ojs\JournalBundle\Entity\Article $article
     */
    private function saveAuthorsData($authors, $article)
    {
        $em = $this->get('doctrine')->getManager();
        foreach ($authors as $authorData) {
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
    }

    /**
     * 
     * @param array $citations
     * @param Article $article
     */
    private function saveCitationData($citations, Article $article)
    {
        $em = $this->get('doctrine')->getManager();
        foreach ($citations as $citationData) {
            $citation = new Citation();
            $citation->setRaw($citationData['raw']);
            $citation->setType($citationData['type']);
            $citation->setOrderNum($citationData['orderNum']);
            $em->persist($citation);
            $em->flush();
            // add relation to article
            $article->addCitation($citation);
            $em->persist($article);
            $em->flush();
            unset($citationData['raw']);
            unset($citationData['type']);
            unset($citationData['orderNum']);
            /// add other data as citation setting  
            foreach ($citationData as $setting => $value) {
                $citationSetting = new CitationSetting();
                $citationSetting->setSetting($setting);
                $citationSetting->setValue($value);
                $citationSetting->setCitation($citation);
                $em->persist($citationSetting);
                $em->flush($citationSetting);
            }
        }
    }

    /**
     * 
     * @param array $files
     * @param Article $article
     * @param string $lang
     */
    private function saveArticleFileData($files, $article, $lang)
    {
        $em = $this->get('doctrine')->getManager();
        foreach ($files as $fileData) {
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
                $articleFile->setLangCode($lang);
            }
            $articleFile->setVersion(1);
            $articleFile->setType($fileData['type']); // @see ArticleFileParams::$FILE_TYPES
            // article full text 

            $em->persist($articleFile);
            $em->flush();
        }
    }

}
