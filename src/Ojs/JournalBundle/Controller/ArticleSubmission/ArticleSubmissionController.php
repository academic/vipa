<?php

namespace Ojs\JournalBundle\Controller\ArticleSubmission;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Document;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\MongoDB\Query\Builder;
use Doctrine\ORM\QueryBuilder;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;
use Ojs\Common\Helper\ActionHelper;
use Ojs\Common\Params\ArticleFileParams;
use Ojs\JournalBundle\Document\ArticleSubmissionProgress;
use Ojs\JournalBundle\Entity\ArticleFile;
use Ojs\JournalBundle\Entity\File;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\UserBundle\Entity\Role;
use Ojs\UserBundle\Entity\UserJournalRole;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Author;
use Ojs\JournalBundle\Entity\Citation;
use Ojs\JournalBundle\Entity\CitationSetting;
use Ojs\JournalBundle\Entity\ArticleAuthor;
use Ojs\WorkflowBundle\Document\ArticleReviewStep;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Article Submission controller.
 *
 */
class ArticleSubmissionController extends Controller
{

    /**
     * Lists all new Article submissions entities.
     * @param  bool     $all
     * @return Response
     */
    public function indexAction($all = false)
    {
        $currentJournal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (
            ($all && !$this->isGranted('VIEW', $currentJournal, 'articles')) ||
            (!$all && !$this->isGranted('CREATE', $currentJournal, 'articles'))
        ) {
            return $this->redirect($this->generateUrl('ojs_user_index'));
        }
        $source1 = new Entity('OjsJournalBundle:Article', 'submission');
        $dm = $this->get('doctrine_mongodb')->getManager();
        $ta = $source1->getTableAlias();

        $source1->manipulateRow(function (Row $row) use ($dm) {
            if (null !== ($row->getField('status'))) {
                $articleId = $row->getField('id');
                $currentStep = $dm->getRepository('OjsWorkflowBundle:ArticleReviewStep')
                    ->findOneBy(array('articleId' => $articleId, 'finishedDate' => null));
                if ($currentStep) {
                    // in case of error if submission is not on mongodb
                    $row->setColor($currentStep->getStep()->getColor());
                    $row->setField('status', "<span style='display:block;background: ".
                        ";display: block'>".$currentStep->getStep()->getStatus()."</span>");
                }
            }

            return $row;
        });

        $source2 = new Document('OjsJournalBundle:ArticleSubmissionProgress');
        $em = $this->getDoctrine()->getManager();
        $router = $this->get('router');
        $repository = $this->get('doctrine_mongodb')->getManager()
            ->getRepository('OjsJournalBundle:InstitutionApplication');

        $source2->manipulateRow(function (Row $row) use ($repository, $em, $router) {
            $row->setRepository($repository);
            if ($row->getField('article_data')) {
                /** @var Array $data */
                $data = $row->getField('article_data');
                $_d = [];

                foreach ($data as $key => $value) {
                    $_d[] = $key.": ".$value['title'];
                }
                $row->setField('article_data', $_d);
            }
            if ($row->getField('journal_id')) {
                $journal = $em->find('OjsJournalBundle:Journal', $row->getField('journal_id'));
                $row->setField('journal_id', (string) $journal->getTitle());
            }

            return $row;
        });
        $user = $this->getUser();

        if ($all) {
            $source1->manipulateQuery(function (QueryBuilder $qb) use ($ta, $currentJournal) {
                $qb->where($ta.'.status = 0');
                $qb->andWhere($ta.'.journalId = '.$currentJournal->getId());

                return $qb;
            });
            $source2->manipulateQuery(function (Builder $query) use ($ta, $currentJournal) {
                $query->where("typeof(this.submitted)=='undefined' || this.submitted===false ".
                    "&& this.journal_id == {$currentJournal->getId()}");

                return $query;
            });
        } else {
            $source1->manipulateQuery(function (QueryBuilder $qb) use ($ta, $user, $currentJournal) {
                $qb->where(
                    $qb->expr()->andX(
                        $qb->expr()->eq($ta.'.status', '0'), $qb->expr()->eq($ta.'.submitterId', $user->getId())
                    )
                );
                $qb->andWhere($ta.'.journalId = '.$currentJournal->getId());

                return $qb;
            });
            $source2->manipulateQuery(function (Builder $query) use ($user, $currentJournal) {
                $query->where("(typeof(this.submitted)=='undefined' || this.submitted===false) ".
                    "&& this.userId=={$user->getId()} && this.journal_id == {$currentJournal->getId()}");

                return $query;
            });
        }

        $gridManager = $this->get('grid.manager');
        $submissionsGrid = $gridManager->createGrid('submission');
        $drafts = $gridManager->createGrid('drafts');
        $submissionsGrid->setSource($source1);
        $drafts->setSource($source2);

        $submissionsGrid->addRowAction(ActionHelper::showAction('article_show', 'id'));
        $submissionsGrid->addRowAction(ActionHelper::editAction('article_edit', 'id'));
        $submissionsGrid->addRowAction(ActionHelper::deleteAction('article_delete', 'id'));

        $rowAction = [];
        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = ActionHelper::submissionResumeAction('article_submission_resume', 'id');
        $rowAction[] = ActionHelper::deleteAction('article_submission_cancel', 'id');
        $actionColumn->setRowActions($rowAction);
        $drafts->addColumn($actionColumn);

        $submissionsGrid->getColumn('status')->setSafe(false);
        $data = ['page' => 'submission',
            'submissions' => $submissionsGrid,
            'drafts' => $drafts,
            'all' => $all, ];

        return $gridManager->getGridManagerResponse('OjsJournalBundle:ArticleSubmission:index.html.twig', $data);
    }

    /**
     *
     * @param  Journal         $journal
     * @return UserJournalRole
     */
    private function checkAndRegisterUserAuthorRole(Journal $journal)
    {
        /**
         * Check if the user is an author of this journal.
         * If not, add author role for this journal
         */
        $checkRole = $this->get('ojs.journal_service')->hasJournalRole('ROLE_AUTHOR');
        $userJournalRole = null;
        if (!$checkRole) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            /** @var Role $role */
            $role = $em->getRepository('OjsUserBundle:Role')->findOneBy(array('role' => 'ROLE_AUTHOR'));
            $userJournalRole = new UserJournalRole();
            $userJournalRole->setUser($user);
            $userJournalRole->setJournal($journal);
            $userJournalRole->setRole($role);
            $em->persist($userJournalRole);
            $em->flush();
        }

        return $userJournalRole;
    }

    /**
     * Show a confirmation to user if he/she wants to register himself as AUTHOR (if he is not).
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function confirmRoleAction(Request $request)
    {
        $checkRole = $this->get('ojs.journal_service')->hasJournalRole('ROLE_AUTHOR');
        if (!$checkRole && $request->get('confirm')) {
            $journal = $this->get("ojs.journal_service")->getSelectedJournal();
            $checkRole = $this->checkAndRegisterUserAuthorRole($journal);
        }

        return $checkRole ?
            $this->redirect($this->generateUrl('article_submission_new')) :
            $this->render('OjsJournalBundle:ArticleSubmission:confirmRole.html.twig', array('roles' => $this->get('ojs.journal_service')->getSelectedJournalRoles()));
    }

    /**
     *
     * @param  integer          $journalId
     * @return RedirectResponse
     */
    public function newWithJournalAction($journalId)
    {
        $journal = $this->getDoctrine()->getRepository('OjsJournalBundle:Journal')->find($journalId);
        if ($this->isGranted('CREATE', $journal, 'articles')) {
            return $this->redirect($this->generateUrl('article_submission_new'));
        }
        $this->throw404IfNotFound($journal);
        $this->get('ojs.journal_service')->setSelectedJournal($journal);
        $checkRole = $this->get('ojs.journal_service')->hasJournalRole('ROLE_AUTHOR');

        return !$checkRole ?
            $this->redirect($this->generateUrl('article_submission_confirm_author')) :
            $this->redirect($this->generateUrl('article_submission_new'));
    }

    /**
     * Displays a form to create a new Article entity.
     * @return Response
     * @throws AccessDeniedException
     */
    public function newAction()
    {
        /**
         * Check if the user is an author of this journal.
         * If not, add author role for this journal
         */
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            return $this->redirect($this->generateUrl('article_submission_confirm_author'));
        }
        $entity = new Article();

        return $this->render('OjsJournalBundle:ArticleSubmission:new.html.twig', array(
            'articleId' => null,
            'entity' => $entity,
            'journal' => $journal,
            'submissionData' => null,
            'fileTypes' => ArticleFileParams::$FILE_TYPES,
            'citationTypes' => $this->container->getParameter('citation_types'),
            'checklist' => [],
            'firstStep' => $this->get('doctrine_mongodb')->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->findOneBy(array('journalid' => $journal->getId(), 'firstStep' => true)),
        ));
    }

    /**
     * Resume action for an article submission
     * @param $submissionId
     * @return Response
     * @throws AccessDeniedException
     */
    public function resumeAction($submissionId)
    {
        $em = $this->getDoctrine()->getManager();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $entity = new Article();
        $articleSubmission = $dm->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->find($submissionId);
        if (!$articleSubmission) {
            throw $this->createNotFoundException('No submission found');
        }
        if (!$this->isGranted('EDIT', $articleSubmission)) {
            throw $this->createAccessDeniedException("Access Denied");
        }
        $data = [
            'submissionId' => $articleSubmission->getId(),
            'submissionData' => $articleSubmission,
            'entity' => $entity,
            'fileTypes' => ArticleFileParams::$FILE_TYPES,
            'citations' => $articleSubmission->getCitations(),
            'citationTypes' => $this->container->getParameter('citation_types'), ];
        $data['checklist'] = json_decode($articleSubmission->getChecklist(), true);
        if ($articleSubmission->getJournalId()) {
            $data['journal'] = $em->getRepository('OjsJournalBundle:Journal')->find($articleSubmission->getJournalId());
        } else {
            $data['journal'] = $this->get("ojs.journal_service")->getSelectedJournal();
        }
        $data['firstStep'] = $this->get('doctrine_mongodb')->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
            ->findOneBy(array('journalid' => $data['journal']->getId(), 'firstStep' => true));

        return $this->render('OjsJournalBundle:ArticleSubmission:new.html.twig', $data);
    }

    /**
     * Preview action for an article submission
     * @param $submissionId
     * @return Response
     * @throws AccessDeniedException
     */
    public function previewAction($submissionId)
    {
        $em = $this->getDoctrine()->getManager();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $articleSubmission = $dm->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->find($submissionId);
        if (!$this->isGranted('EDIT', $articleSubmission)) {
            throw $this->createAccessDeniedException("Access Denied");
        }
        if ($articleSubmission->getSubmitted()) {
            throw $this->createAccessDeniedException("Access Denied This submission has already been submitted.");
        }
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($articleSubmission->getJournalId());

        return $this->render('OjsJournalBundle:ArticleSubmission:preview.html.twig', array(
            'submissionId' => $articleSubmission->getId(),
            'submissionData' => $articleSubmission,
            'journal' => $journal,
            'fileTypes' => ArticleFileParams::$FILE_TYPES,
        ));
    }

    /**
     * Finish action for an article submission.
     * This action moves article's data from mongodb to mysql
     * @param  Request                                     $request
     * @return RedirectResponse
     * @throws NotFoundHttpException|AccessDeniedException
     */
    public function finishAction(Request $request)
    {
        $submissionId = $request->get('submissionId');
        if (!$submissionId) {
            throw $this->createNotFoundException('There is no submission with this Id.');
        }
        $dm = $this->get('doctrine_mongodb')->getManager();

        $em = $this->getDoctrine()->getManager();
        /* @var  $articleSubmission ArticleSubmissionProgress */
        $articleSubmission = $dm->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->find($submissionId);
        if (!$articleSubmission) {
            throw $this->createNotFoundException('Submission not found.');
        }
        if (!$this->isGranted('EDIT', $articleSubmission)) {
            throw $this->createAccessDeniedException("Access Denied");
        }
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($articleSubmission->getJournalId());
        if (!$journal) {
            throw $this->createNotFoundException('Journal not found');
        }

        $article = $this->saveArticleSubmission($articleSubmission, $journal);

// get journal's first workflow step
        $firstStep = $this->get('doctrine_mongodb')->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
            ->findOneBy(array('journalid' => $journal->getId(), 'firstStep' => true));
        if ($firstStep) {
            $reviewStep = new ArticleReviewStep();
            $reviewStep->setArticleId($article->getId());
            $reviewStep->setSubmitterId($this->getUser()->getId());
            $reviewStep->setStartedDate(new \DateTime());
            $reviewStep->setStatusText($firstStep->getStatus());
            $reviewStep->setPrimaryLanguage($articleSubmission->getPrimaryLanguage());
            $reviewStep->setCompetingOfInterest($articleSubmission->getCompetingOfInterest());
            $reviewStep->setArticleRevised(array(
                'articleData' => $articleSubmission->getArticleData(),
                'authors' => $articleSubmission->getAuthors(),
                'citation' => $articleSubmission->getCitations(),
                'files' => $articleSubmission->getFiles(), ));

            $deadline = new \DateTime();
            $deadline->modify("+".$firstStep->getMaxdays()." day");
            $reviewStep->setReviewDeadline($deadline);
            $reviewStep->setRootNode(true);
            $reviewStep->setStep($firstStep);
            $reviewStep->setNote($request->get('notes'));
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
     *
     * @param  ArticleSubmissionProgress $articleSubmission
     * @param $journal
     * @return Article
     */
    private function saveArticleSubmission(ArticleSubmissionProgress $articleSubmission, $journal)
    {
        /* article submission data will be moved from mongodb to mysql */
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

        $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('submission.success'));
// @todo give ref. link or code or directives to author
        return $article;
    }

    /**
     *
     * @return Article
     *
     * @param $articlePrimaryData
     * @param $journal
     * @param $lang
     * @return Article
     */
    private function saveArticlePrimaryData($articlePrimaryData, $journal, $lang)
    {
        $em = $this->getDoctrine()->getManager();
        $article = new Article();
        $article->setPrimaryLanguage($lang);
        $article->setJournal($journal);
        $article->setTitle($articlePrimaryData['title']);
        $article->setAbstract($articlePrimaryData['abstract']);
        $article->setKeywords($articlePrimaryData['keywords']);
        $article->setSubjects($articlePrimaryData['subjects']);
        $article->setSubtitle($articlePrimaryData ['title']);
        $article->setSubmitterId($this->getUser()->getId());
        $article->setStatus(0);

        $em->persist($article);
        $em->flush();

        return $article;
    }

    /**
     *
     * @param array   $articleData
     * @param Article $article
     */
    private function saveArticleTranslations($articleData, Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var TranslationRepository $translationRepository */
        $translationRepository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        foreach ($articleData as $locale => $data) {
            $translationRepository->translate($article, 'title', $locale, $data['title'])->translate($article, 'abstract', $locale, $data['abstract'])
                ->translate($article, 'keywords', $locale, $data['keywords'])
                ->translate($article, 'subjects', $locale, $data['subjects']);
        }
        $em->persist($article);
        $em->flush();
    }

    /**
     * @param $authors
     * @param $article
     */
    private function saveAuthorsData($authors, $article)
    {
        $em = $this->getDoctrine()->getManager();
        foreach ($authors as $authorData) {
            $author = new Author();
// check institution
            $institution = $em->getRepository('OjsJournalBundle:Institution')->find($authorData['institution']);
            if (!$institution) {
                $institution = $em->getRepository('OjsJournalBundle:Institution')->findOneBy(array('name' => trim($authorData['institution'])));
            }
            if (!$institution) {
                $institution = new Institution();
                $institution->setName(trim($authorData['institution']));
                $institution->setVerified(false);
                $em->persist($institution);
                $em->flush();
            }
            $author->setInstitution($institution);
            $author->setEmail($authorData['email']);
            $author->setTitle($authorData['title']);
            $author->setInitials($authorData['initials']);
            $author->setFirstName($authorData['firstName']);
            $author->setLastName($authorData['lastName']);
            $author->setMiddleName($authorData['middleName']);
            $author->setSummary($authorData['summary']);
            $author->setOrcid($authorData['orcid']);
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
     * @param $citations
     * @param Article $article
     */
    private function saveCitationData($citations, Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        foreach ($citations as $citationData) {
            $citation = new Citation();
            $citation->setRaw($citationData['raw']);
            $citation->setType($citationData['citationtype']);
            $citation->setOrderNum($citationData['orderNum']);
            $em->persist(
                $citation);
            $em->flush();
// add relation to article
            $article->addCitation($citation);
            $em->persist($article);
            $em->flush();
            unset($citationData['raw']);
            unset($citationData['citationtype']);
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
     * @param $files
     * @param $article
     * @param string $lang
     */
    private function saveArticleFileData($files, Article $article, $lang)
    {
        $em = $this->getDoctrine()->getManager();
        foreach ($files as $fileData) {
            $file = new File();
            $file->setPath($fileData['article_file']);
            $file->setName($fileData['article_file']);
            $file->setMimeType($fileData['article_file_mime_type']);
            $file->setSize($fileData['article_file_size']);

// @todo add get mime type and name
            $em->persist($file);
            $em->flush();
            $articleFile = new ArticleFile();
            $articleFile->setArticle($article);
            $articleFile->setFile($file);

            isset($fileData['title']) && $articleFile->setTitle($fileData['title']);
            isset($fileData['desc']) && $articleFile->setDescription($fileData['desc']);
            isset($fileData['keywords']) && $articleFile->setKeywords($fileData['keywords']);
            $articleFile->setLangCode(isset($fileData['lang']) ? $fileData['lang'] : $lang);

            $articleFile->setVersion(1);
            $articleFile->setType($fileData['type']); // @see ArticleFileParams::$FILE_TYPES
// article full text

            $em->persist($articleFile);
            $em->flush();
        }
    }

    /**
     * Returns requested orcid user profile details
     * @param  Request      $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function getOrcidAuthorAction(Request $request)
    {
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw $this->createAccessDeniedException("Access Denied");
        }
        $getAuthor = null;
        if ($request->get('orcidAuthorId')) {
            $orcidAuthorId = $request->get('orcidAuthorId');
            $orcidService = $this->get('ojs.orcid_service');
            $getAuthor = $orcidService->getBio($orcidAuthorId, '');
        }
        $response = new JsonResponse();
        $response->setData($getAuthor);

        return $response;
    }

    public function cancelAction($id)
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        /** @var ArticleSubmissionProgress $as */
        $as = $dm->find('OjsJournalBundle:ArticleSubmissionProgress', $id);
        if (!$as) {
            throw new NotFoundHttpException();
        }
        if (!$this->isGranted('EDIT', $as)) {
            throw $this->createAccessDeniedException("Access Denied");
        }
        $dm->remove($as);
        $dm->flush();
        $session = $this->get('session');
        $flashBag = $session->getFlashBag();
        $flashBag->add('success', $this->get('translator')->trans("Successfully deleted."));

        return RedirectResponse::create($this->get('router')->generate('article_submissions_me'));
    }
}
