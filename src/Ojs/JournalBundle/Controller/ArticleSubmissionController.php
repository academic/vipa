<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Document;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\MongoDB\Query\Builder;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\Common\Params\ArticleFileParams;
use Ojs\JournalBundle\Entity\ArticleSubmissionProgress;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\ArticleAuthor;
use Ojs\JournalBundle\Entity\ArticleFile;
use Ojs\JournalBundle\Entity\Author;
use Ojs\JournalBundle\Entity\Citation;
use Ojs\JournalBundle\Entity\File;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalRole;
use Ojs\JournalBundle\Form\Type\ArticleSubmission\Step2Type;
use Ojs\JournalBundle\Form\Type\ArticleSubmission\Step4CitationType;
use Ojs\UserBundle\Entity\Role;
use Ojs\WorkflowBundle\Document\ArticleReviewStep;
use Ojs\WorkflowBundle\Document\JournalWorkflowStep;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Ojs\Common\Services\GridAction;
use Ojs\Common\Services\JournalService;

/**
 * Article Submission controller.
 *
 */
class ArticleSubmissionController extends Controller
{

    /**
     * Lists all new Article submissions entities.
     * @param  bool $all
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

        $source1->manipulateRow(
            function (Row $row) use ($dm) {
                if (null !== ($row->getField('status'))) {
                    $articleId = $row->getField('id');
                    $currentStep = $dm->getRepository('OjsWorkflowBundle:ArticleReviewStep')
                        ->findOneBy(array('articleId' => $articleId, 'finishedDate' => null));
                    if ($currentStep) {
                        // in case of error if submission is not on mongodb
                        $row->setColor($currentStep->getStep()->getColor());
                        $row->setField(
                            'status',
                            "<span style='display:block;background: ".
                            ";display: block'>".$currentStep->getStep()->getStatus()."</span>"
                        );
                    }
                }

                return $row;
            }
        );

        $source2 = new Document('OjsJournalBundle:ArticleSubmissionProgress');
        $em = $this->getDoctrine()->getManager();
        $router = $this->get('router');
        $repository = $em->getRepository('OjsJournalBundle:Institution');

        $source2->manipulateRow(
            function (Row $row) use ($repository, $em, $router) {
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
                    $row->setField('journal_id', (string)$journal->getTitle());
                }

                return $row;
            }
        );
        $user = $this->getUser();

        if ($all) {
            $source1->manipulateQuery(
                function (QueryBuilder $qb) use ($ta, $currentJournal) {
                    $qb->where($ta.'.status = 0');
                    $qb->andWhere($ta.'.journalId = '.$currentJournal->getId());

                    return $qb;
                }
            );
            $source2->manipulateQuery(
                function (Builder $query) use ($ta, $currentJournal) {
                    $query->where(
                        "typeof(this.submitted)=='undefined' || this.submitted===false ".
                        "&& this.journal_id == {$currentJournal->getId()}"
                    );

                    return $query;
                }
            );
        } else {
            $source1->manipulateQuery(
                function (QueryBuilder $qb) use ($ta, $user, $currentJournal) {
                    $qb->where(
                        $qb->expr()->andX(
                            $qb->expr()->eq($ta.'.status', '0'),
                            $qb->expr()->eq($ta.'.submitterId', $user->getId())
                        )
                    );
                    $qb->andWhere($ta.'.journalId = '.$currentJournal->getId());

                    return $qb;
                }
            );
            $source2->manipulateQuery(
                function (Builder $query) use ($user, $currentJournal) {
                    $query->where(
                        "(typeof(this.submitted)=='undefined' || this.submitted===false) ".
                        "&& this.userId=={$user->getId()} && this.journal_id == {$currentJournal->getId()}"
                    );

                    return $query;
                }
            );
        }

        $gridManager = $this->get('grid.manager');
        $submissionsGrid = $gridManager->createGrid('submission');
        $drafts = $gridManager->createGrid('drafts');
        $submissionsGrid->setSource($source1);
        $drafts->setSource($source2);
        /** @var GridAction $gridAction */
        $gridAction = $this->get('grid_action');

        $submissionsGrid->addRowAction($gridAction->showAction('ojs_journal_article_show', ['id', 'journalId' => $currentJournal->getId()]));
        $submissionsGrid->addRowAction($gridAction->editAction('ojs_journal_article_edit', ['id', 'journalId' => $currentJournal->getId()]));
        $submissionsGrid->addRowAction($gridAction->deleteAction('ojs_journal_article_delete', ['id', 'journalId' => $currentJournal->getId()]));

        $rowAction = [];
        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->submissionResumeAction('article_submission_resume', 'id');
        $rowAction[] = $gridAction->deleteAction('article_submission_cancel', 'id');
        $actionColumn->setRowActions($rowAction);
        $drafts->addColumn($actionColumn);

        $submissionsGrid->getColumn('status')->setSafe(false);
        $data = [
            'page' => 'submission',
            'submissions' => $submissionsGrid,
            'drafts' => $drafts,
            'all' => $all,
        ];

        return $gridManager->getGridManagerResponse('OjsJournalBundle:ArticleSubmission:index.html.twig', $data);
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
         * @var Journal $journal
         */
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        if (!$journal) {
            return $this->redirect($this->generateUrl('user_join_journal'));
        }
        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            return $this->redirect($this->generateUrl('article_submission_confirm_author'));
        }
        $dm = $this->get('doctrine_mongodb');
        $firstStep  = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
            ->findOneBy(array('journalid' => $journal->getId(), 'firstStep' => true));

        return $this->render(
            'OjsJournalBundle:ArticleSubmission:new.html.twig',
            array(
                'journal' => $journal,
                'step' => '1',
                'checklist' => [],
                'firstStep' => $firstStep
            )
        );
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
        /** @var ArticleSubmissionProgress $articleSubmission */
        $articleSubmission = $em->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->find($submissionId);
        if (!$articleSubmission) {
            throw $this->createNotFoundException('No submission found');
        }
        if($articleSubmission->getSubmitted()){
            throw new AccessDeniedException('You can \'t edit article after submit');
        }
        /** @var Article $article */
        $article = $articleSubmission->getArticle();

        $articleTypes = $em->getRepository('OjsJournalBundle:ArticleTypes')->findAll();
        $articleAuthors = $em->getRepository('OjsJournalBundle:ArticleAuthor')->findByArticle($article);
        $articleFiles = $em->getRepository('OjsJournalBundle:ArticleFile')->findByArticle($article);
        $checklist = json_decode($articleSubmission->getChecklist(), true);
        $step2Form = $this->createForm(new Step2Type(), $article, ['method' => 'POST'])->createView();
        $citationForms = [];
        $citationTypes = array_keys($this->container->getParameter('citation_types'));
        foreach($article->getCitations() as $citation){
            $citationForms[] = $this->createForm(new Step4CitationType(), $citation, [
                'method' => 'POST',
                'citationTypes' => $citationTypes
            ])->createView();
        }
        $citationFormTemplate = $this->createForm(new Step4CitationType(), new Citation(), [
            'method' => 'POST',
            'citationTypes' => $citationTypes
        ])->createView();
        $data = [
            'submissionId' => $articleSubmission->getId(),
            'submissionData' => $articleSubmission,
            'fileTypes' => ArticleFileParams::$FILE_TYPES,
            'citations' => $article->getCitations(),
            'citationForms' => $citationForms,
            'articleAuthors' => $articleAuthors,
            'citationFormTemplate' => $citationFormTemplate,
            'articleTypes' => $articleTypes,
            'journal' => $articleSubmission->getJournal(),
            'checklist' => $checklist,
            'step' => $articleSubmission->getCurrentStep(),
            'step2Form' => $step2Form,
            'articleFiles' => $articleFiles
        ];
        return $this->render('OjsJournalBundle:ArticleSubmission:new.html.twig', $data);
    }

    /**
     * Step Control Action
     * @param Request $request
     * @param null $step
     * @return JsonResponse|Response
     */
    public function stepControlAction(Request $request, $step = null)
    {
        switch($step){
            case 1:
                return $this->step1Control($request);
            case 2:
                return $this->step2Control($request);
            case 3:
                return $this->step3Control($request);
            case 4:
                return $this->step4Control($request);
            case 5:
                return $this->step5Control($request);
            default:
                throw new NotFoundHttpException();
        }
    }

    /**
     * @param  Request $request
     * @return JsonResponse
     */
    public function step1Control(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $article = new Article();
        $article->setJournal($selectedJournal);
        $article->setSubmitterId($user->getId());
        $article->setSetupStatus(0);
        $article->setTitle('');
        $em->persist($article);
        $em->flush();

        $articleSubmission = new ArticleSubmissionProgress();
        $articleSubmission->setArticle($article);
        $articleSubmission->setUser($user);
        $articleSubmission->setJournal($selectedJournal);
        $articleSubmission->setChecklist(json_encode($request->get('checklistItems')));
        $articleSubmission->setSubmitted(false);
        $articleSubmission->setCurrentStep(2);
        $articleSubmission->setCompetingOfInterest($request->get('competingOfInterest'));
        $em->persist($articleSubmission);
        $em->flush();

        return new JsonResponse(
            [
                'success' => "1",
                'resumeLink' => $this->generateUrl('article_submission_resume', [
                        'submissionId' =>$articleSubmission->getId()
                    ]).'#2'
            ]
        );
    }

    /**
     * submit new article - step2 - get article base data without author info.
     * @param  Request $request
     * @return JsonResponse
     */
    private function step2Control(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $submissionId = $request->get('submissionId');
        /** @var ArticleSubmissionProgress $articleSubmission */
        $articleSubmission = $em->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->find($submissionId);
        $article = $articleSubmission->getArticle();

        $step2Form = $this->createForm(new Step2Type(), $article);
        $step2Form->handleRequest($request);
        if ($step2Form->isValid()) {
            $articleSubmission->setCurrentStep(3);
            $em->flush();
            return new JsonResponse(['success' => '1']);
        } else {
            return new JsonResponse(['success' => '0', 'errors' => $step2Form->getErrors()]);
        }
    }

    /**
     * @param  Request $request
     * @return JsonResponse|Response
     */
    public function step3Control(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $submissionId = $request->get("submissionId");
        /** @var ArticleSubmissionProgress $articleSubmission */
        $articleSubmission = $em->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->find($submissionId);
        $this->throw404IfNotFound($articleSubmission);
        $article = $articleSubmission->getArticle();
        $authorsData = json_decode($request->request->get('authorsData'));
        $articleAuthors = $em->getRepository('OjsJournalBundle:ArticleAuthor')->findByArticle($article);
        $authorIds = [];
        if (empty($authorsData)) {
            return new Response('Missing argument', 400);
        }
        for ($i = 0; $i < count($authorsData); $i++) {
            if (empty($authorsData[$i]->firstName)) {
                unset($authorsData[$i]);
            }else{
                $authorData = $authorsData[$i];
                if(empty($authorData->authorid)){
                    $author = new Author();
                    $articleAuthor = new ArticleAuthor();
                }else{
                    $authorIds[] = $authorData->authorid;
                    $author = $em->getRepository('OjsJournalBundle:Author')->find($authorData->authorid);
                    $articleAuthor = $em->getRepository('OjsJournalBundle:ArticleAuthor')->findOneBy([
                        'article' => $article,
                        'author' => $author
                    ]);
                }

                $author->setFirstName($authorData->firstName);
                $author->setEmail($authorData->email);
                $author->setTitle($authorData->title);
                $author->setInitials($authorData->initials);
                $author->setMiddleName($authorData->middleName);
                $author->setLastName($authorData->lastName);
                $author->setPhone($authorData->phone);
                $author->setSummary($authorData->summary);
                $author->setOrcid($authorData->orcid);
                if(!empty($authorData->institution)){
                    /** @var Institution $institution */
                    $institution = $em->getRepository('OjsJournalBundle:Institution')->find($authorData->institution);
                    $author->setInstitution($institution);
                }
                $em->persist($author);

                $articleAuthor->setArticle($article);
                $articleAuthor->setAuthor($author);
                $articleAuthor->setAuthorOrder($authorData->order);
                $em->persist($articleAuthor);
                $em->flush();
            }
        }
        //remove removed authors
        /** @var ArticleAuthor $articleAuthor */
        foreach($articleAuthors as $articleAuthor){
            if(!in_array($articleAuthor->getAuthorId(), $authorIds)){
                $em->remove($articleAuthor);
            }
        }
        $em->flush();
        return new JsonResponse(['success' => 1]);
    }

    /**
     * @param  Request $request
     * @return JsonResponse|Response
     */
    private function step4Control(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $submissionId = $request->get("submissionId");
        /** @var ArticleSubmissionProgress $articleSubmission */
        $articleSubmission = $em->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->find($submissionId);
        $this->throw404IfNotFound($articleSubmission);
        $article = $articleSubmission->getArticle();
        $citationsData = json_decode($request->request->get('citeData'), true);
        $article->getCitations()->clear();
        $citationIds = [];
        foreach ($citationsData as $citationData) {

            if(empty($citationData['article_submission_citation[id]'])){
                $citation = new Citation();
            }else{
                $citationIds[] = $citationData['article_submission_citation[id]'];
                $citation = $em->getRepository('OjsJournalBundle:Citation')->find($citationData['article_submission_citation[id]']);
            }
            $citation->setRaw($citationData['article_submission_citation[raw]']);
            $citation->setOrderNum($citationData['article_submission_citation[orderNum]']);
            $citation->setType($citationData['article_submission_citation[type]']);
            $em->persist($citation);
            $em->flush();

            $article->addCitation($citation);
            $em->flush();
        }
        $em->flush();
        return new JsonResponse(['success' => 1]);
    }

    /**
     * @param  Request $request
     * @return JsonResponse|Response
     */
    private function step5Control(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $filesData = json_decode($request->request->get('filesData'));
        $submissionId = $request->get("submissionId");
        /** @var ArticleSubmissionProgress $articleSubmission */
        $articleSubmission = $em->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->find($submissionId);
        $this->throw404IfNotFound($articleSubmission);

        if (empty($filesData) || !$submissionId || !$articleSubmission) {
            return new Response('Missing argument', 400);
        }

        $article = $articleSubmission->getArticle();
        $articleFiles = $em->getRepository('OjsJournalBundle:ArticleFile')->findByArticle($article);
        $fileIds = [];
        for ($i = 0; $i < count($filesData); $i++) {
            if (strlen($filesData[$i]->article_file) < 1) {
                unset($filesData[$i]);
            }else{
                $fileData = $filesData[$i];
                if(empty($fileData->id)){
                    $file = new File();
                    $articleFile = new ArticleFile();
                }else{
                    $fileIds[] = $fileData->id;
                    $file = $em->getRepository('OjsJournalBundle:File')->find($fileData->id);
                    $articleFile = $em->getRepository('OjsJournalBundle:ArticleFile')->findOneBy([
                        'article' => $article,
                        'file' => $file
                    ]);
                }

                $file->setMimeType($fileData->article_file_mime_type);
                $file->setName($fileData->title);
                $file->setPath($fileData->article_file);
                $file->setSize($fileData->article_file_size);
                $em->persist($file);

                $articleFile->setArticle($article);
                $articleFile->setFile($file);
                $articleFile->setType($fileData->type);
                $articleFile->setTitle($fileData->title);
                $articleFile->setVersion(1);
                $articleFile->setDescription($fileData->desc);
                $articleFile->setKeywords($fileData->keywords);
                $articleFile->setLangCode($fileData->lang);
                $em->persist($articleFile);
                $em->flush();
            }
        }
        //remove removed files
        /** @var ArticleFile $articleFile */
        foreach($articleFiles as $articleFile){
            if(!in_array($articleFile->getFileId(), $fileIds)){
                $em->remove($articleFile);
            }
        }
        $em->flush();
        return new JsonResponse(
            array(
                'redirect' => $this->generateUrl(
                    'article_submission_preview',
                    array('submissionId' => $submissionId)
                )
            )
        );
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
        /** @var ArticleSubmissionProgress $articleSubmission */
        $articleSubmission = $em->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->find($submissionId);
        if ($articleSubmission->getSubmitted()) {
            throw $this->createAccessDeniedException("Access Denied This submission has already been submitted.");
        }
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($articleSubmission->getJournal()->getId());

        return $this->render(
            'OjsJournalBundle:ArticleSubmission:preview.html.twig',
            array(
                'submissionId' => $articleSubmission->getId(),
                'submissionData' => $articleSubmission,
                'journal' => $journal,
                'fileTypes' => ArticleFileParams::$FILE_TYPES,
            )
        );
    }

    /**
     * Finish action for an article submission.
     * @param  Request $request
     * @return RedirectResponse
     * @throws NotFoundHttpException|AccessDeniedException
     */
    public function finishAction(Request $request)
    {
        $submissionId = $request->get('submissionId');
        if (!$submissionId) {
            throw $this->createNotFoundException('There is no submission with this Id.');
        }
        $dm = $this->get('doctrine_mongodb');
        $em = $this->getDoctrine()->getManager();
        /* @var  $articleSubmission ArticleSubmissionProgress */
        $articleSubmission = $em->getRepository('OjsJournalBundle:ArticleSubmissionProgress')->find($submissionId);
        $article = $articleSubmission->getArticle();
        $journal = $article->getJournal();
        if (!$articleSubmission) {
            throw $this->createNotFoundException('Submission not found.');
        }

        // get journal's first workflow step
        /** @var JournalWorkflowStep $firstStep */
        $firstStep = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
            ->findOneBy(array('journalid' => $journal->getId(), 'firstStep' => true));
        if ($firstStep) {
            $reviewStep = new ArticleReviewStep();
            $reviewStep->setArticleId($article->getId());
            $reviewStep->setSubmitterId($articleSubmission->getUser()->getId());
            $reviewStep->setStartedDate(new \DateTime());
            $reviewStep->setStatusText($firstStep->getStatus());
            $reviewStep->setCompetingOfInterest($articleSubmission->getCompetingOfInterest());
            $reviewStep->setArticleRevised(
                array(
                    'articleId' => $article->getId()
                )
            );
            $deadline = new \DateTime();
            $deadline->modify("+".$firstStep->getMaxDays()." day");
            $reviewStep->setReviewDeadline($deadline);
            $reviewStep->setRootNode(true);
            $reviewStep->setStep($firstStep);
            $reviewStep->setNote($request->get('notes'));
            $dm->persist($reviewStep);
            $dm->flush();
        }
        $article->setStatus(0);
        $article->setSetupStatus(1);
        $articleSubmission->setSubmitted(1);
        $em->flush();
        return $this->redirect($this->generateUrl('article_submissions_me'));
    }

    /**
     * Show a confirmation to user if he/she wants to register himself as AUTHOR (if he is not).
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function confirmRoleAction(Request $request)
    {
        /** @var JournalService $journalService */
        $journalService = $this->get('ojs.journal_service');
        $checkRole = $journalService->hasJournalRole('ROLE_AUTHOR');
        if (!$checkRole && $request->get('confirm')) {
            $journal = $journalService->getSelectedJournal();
            $checkRole = $this->checkAndRegisterUserAuthorRole($journal);
        }

        return $checkRole ?
            $this->redirect($this->generateUrl('article_submission_new')) :
            $this->render(
                'OjsJournalBundle:ArticleSubmission:confirmRole.html.twig',
                array('journalRoles' => $journalService->getSelectedJournalRoles())
            );
    }

    /**
     *
     * @param  Journal $journal
     * @return JournalRole
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
            $userJournalRole = new JournalRole();
            $userJournalRole->setUser($user);
            $userJournalRole->setJournal($journal);
            $userJournalRole->setRole($role);
            $em->persist($userJournalRole);
            $em->flush();
        }

        return $userJournalRole;
    }

    /**
     *
     * @param  integer $journalId
     * @return RedirectResponse
     */
    public function newWithJournalAction($journalId)
    {
        /** @var Journal $journal */
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
     * @return Response
     */
    public function widgetAction()
    {
        $data = [];
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $data['journal'] = $journal;

        return $this->render('OjsJournalBundle:ArticleSubmission:preSubmission.html.twig', $data);
    }

    /**
     * Returns requested orcid user profile details
     * @param  Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function getOrcidAuthorAction(Request $request)
    {
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw $this->createAccessDeniedException("ojs.403");
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

    /**
     * @param $id
     * @return Response|static
     */
    public function cancelAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var ArticleSubmissionProgress $articleSubmission */
        $articleSubmission = $em->find('OjsJournalBundle:ArticleSubmissionProgress', $id);
        if (!$articleSubmission) {
            throw new NotFoundHttpException();
        }
        $em->remove($articleSubmission);
        $em->remove($articleSubmission->getArticle());
        $em->flush();
        $this->addFlash('success', $this->get('translator')->trans('deleted'));
        return $this->redirectToRoute('article_submissions_me');
    }
}
