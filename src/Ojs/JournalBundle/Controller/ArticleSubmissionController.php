<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\CoreBundle\Params\ArticleFileParams;
use Ojs\CoreBundle\Service\GridAction;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\ArticleAuthor;
use Ojs\JournalBundle\Entity\ArticleFile;
use Ojs\JournalBundle\Entity\ArticleRepository;
use Ojs\JournalBundle\Entity\ArticleSubmissionFile;
use Ojs\JournalBundle\Entity\ArticleSubmissionStart;
use Ojs\JournalBundle\Entity\Author;
use Ojs\JournalBundle\Entity\Citation;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalSubmissionFile;
use Ojs\JournalBundle\Entity\JournalUser;
use Ojs\JournalBundle\Entity\SubmissionChecklist;
use Ojs\JournalBundle\Event\ArticleSubmitEvent;
use Ojs\JournalBundle\Event\ArticleSubmitEvents;
use Ojs\JournalBundle\Form\Type\ArticlePreviewType;
use Ojs\JournalBundle\Form\Type\ArticleStartType;
use Ojs\JournalBundle\Form\Type\ArticleSubmissionType;
use Ojs\UserBundle\Entity\Role;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Article Submission controller.
 *
 */
class ArticleSubmissionController extends Controller
{

    /**
     * Lists all new Article submissions entities.
     *
     * @param Request $request
     * @param bool $all
     * @return RedirectResponse|Response
     */
    public function indexAction(Request $request, $all = false)
    {
        $translator = $this->get('translator');
        /** @var Journal $currentJournal */
        $currentJournal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (
            ($all && !$this->isGranted('VIEW', $currentJournal, 'articles')) ||
            (!$all && !$this->isGranted('CREATE', $currentJournal, 'articles'))
        ) {
            return $this->redirect($this->generateUrl('ojs_user_index'));
        }

        $user = $this->getUser();

        $source1 = new Entity('OjsJournalBundle:Article', 'submission');
        $source2 = new Entity('OjsJournalBundle:Article', 'submission');
        $source1TableAlias = $source1->getTableAlias();
        $source2TableAlias = $source2->getTableAlias();

        $source1->manipulateQuery(
            function (QueryBuilder $qb) use ($source1TableAlias, $user, $currentJournal, $all) {
                $qb->andWhere($source1TableAlias.'.status IN (:notDraftStatuses)')
                    ->setParameter('notDraftStatuses', array(-3, -2, 0 ,1));
                if(!$all){
                    $qb->andWhere($source1TableAlias.'.submitterUser = :user')
                        ->setParameter('user', $user);
                }
                return $qb;
            }
        );

        $source2->manipulateQuery(
            function (QueryBuilder $qb) use ($source2TableAlias, $user, $currentJournal, $all) {
                $qb->andWhere($source2TableAlias.'.status = :status')
                    ->setParameter('status', -1);
                if(!$all){
                    $qb->andWhere($source2TableAlias.'.submitterUser = :user')
                        ->setParameter('user', $user);
                }
            }
        );

        $gridManager = $this->get('grid.manager');
        $submissionsGrid = $gridManager->createGrid('submission');
        $drafts = $gridManager->createGrid('drafts');
        $source1->manipulateRow(
            function (Row $row) use ($translator, $currentJournal) {
                /** @var Article $entity */
                $entity = $row->getEntity();
                $entity->setDefaultLocale($currentJournal->getMandatoryLang()->getCode());

                $row->setField('status', $translator->trans($entity->getStatusText()));

                $row->setField('title', $entity->getTitle());
                return $row;
            }
        );

        $source2->manipulateRow(
            function (Row $row) use ($translator, $request) {
                $entity = $row->getEntity();
                /** @var Article $entity */
                $entity->setDefaultLocale($request->getDefaultLocale());

                $row->setField('status', $translator->trans($entity->getStatusText()));
                $row->setField('title', $entity->getTitle());
                return $row;
            }
        );

        $submissionsGrid->setSource($source1);
        $drafts->setSource($source2);
        /** @var GridAction $gridAction */
        $gridAction = $this->get('grid_action');
        /**
        $submissionsGrid->addRowAction(
            $gridAction->showAction('ojs_journal_article_show', ['id', 'journalId' => $currentJournal->getId()])
        );

        $submissionsGrid->addRowAction(
            $gridAction->editAction('ojs_journal_article_edit', ['id', 'journalId' => $currentJournal->getId()])
        );

        $submissionsGrid->addRowAction(
            $gridAction->deleteAction('ojs_journal_article_delete', ['id', 'journalId' => $currentJournal->getId()])
        );
         * **/

        $rowAction = [];
        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->submissionResumeAction('ojs_journal_submission_edit', ['journalId' => $currentJournal->getId(), 'id']);
        $rowAction[] = $gridAction->submissionCancelAction('ojs_journal_submission_cancel', ['journalId' => $currentJournal->getId(), 'id']);
        $actionColumn->setRowActions($rowAction);
        $drafts->addColumn($actionColumn);
        $data = [
            'page' => 'submission',
            'submissions' => $submissionsGrid,
            'drafts' => $drafts,
            'all' => $all,
        ];

        return $gridManager->getGridManagerResponse('OjsJournalBundle:ArticleSubmission:index.html.twig', $data);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        if ($this->submissionsNotAllowed()) {
            return $this->respondAsNotAllowed();
        }

        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');

        if(!$session->has('submissionFiles')){
            return $this->redirectToRoute(
                'ojs_journal_submission_start',
                array('journalId' => $journal->getId())
            );
        }

        $defaultCountryId = $this->container->getParameter('country_id');
        $defaultCountry = $em->getRepository('OkulBilisimLocationBundle:Country')->find($defaultCountryId);
        /** @var User $user */
        $user = $this->getUser();
        if (!$journal) {
            return $this->redirect($this->generateUrl('ojs_journal_user_register_list'));
        }

        $article = new Article();
        $articleAuthor = new ArticleAuthor();

        $author = new Author();
        $author
            ->setUser($user)
            ->setFirstName($user->getFirstName())
            ->setLastName($user->getLastName())
            ->setCountry($defaultCountry)
            ->setEmail($user->getEmail())
            ->setAddress($user->getAddress());

        $articleAuthor->setAuthor($author);
        $article
            ->setSubmitterUser($user)
            ->setStatus(-1)
            ->setJournal($journal)
            ->addCitation(new Citation())
            ->addArticleFile(new ArticleFile())
            ->addArticleAuthor($articleAuthor);

        $locales = [];
        $submissionLangObjects = $journal->getLanguages();
        foreach ($submissionLangObjects as $submissionLangObject) {
            $locales[] = $submissionLangObject->getCode();
        }

        $defaultLocale = $journal->getMandatoryLang()->getCode();
        $article->setCurrentLocale($defaultLocale);

        $form = $this->createCreateForm($article, $journal, $locales, $defaultLocale);
        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            $k = 0;
            foreach ($article->getArticleAuthors() as $f_articleAuthor) {
                $f_articleAuthor->setAuthorOrder($k);
                $f_articleAuthor->setArticle($article);
                $k++;
            }

            $i = 0;
            foreach ($article->getCitations() as $f_citations) {
                $f_citations->setOrderNum($i);
                $i++;
            }

            foreach ($article->getArticleFiles() as $f_articleFile) {
                $f_articleFile->setArticle($article);
                $f_articleFile->setVersion(0);
            }

            $journalSubmissionFiles = $em
                ->getRepository('OjsJournalBundle:JournalSubmissionFile')
                ->findBy([
                    'visible' => true,
                    'locale' => $request->getLocale()
                ]);

            foreach($session->get('submissionFiles') as $fileKey => $submissionFile){
                if(!is_null($submissionFile)){
                    /** @var JournalSubmissionFile $journalEqualFile */
                    $journalEqualFile = $journalSubmissionFiles[$fileKey];
                    $articleSubmissionFile = new ArticleSubmissionFile();
                    $articleSubmissionFile
                        ->setTitle($journalEqualFile->getTitle())
                        ->setDetail($journalEqualFile->getDetail())
                        ->setLocale($journalEqualFile->getLocale())
                        ->setRequired($journalEqualFile->getRequired())
                        ->setFile($submissionFile)
                        ->setArticle($article);
                    $em->persist($articleSubmissionFile);
                }
            }

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute(
                'ojs_journal_submission_preview',
                array(
                    'journalId' => $journal->getId(),
                    'articleId' => $article->getId()
                )
            );
        }

        return $this->render(
            'OjsJournalBundle:ArticleSubmission:new.html.twig',
            array(
                'article' => $article,
                'journal' => $journal,
                'form' => $form->createView(),
            )
        );
    }

    private function submissionsNotAllowed()
    {
        $permissionSetting = $this
            ->getDoctrine()
            ->getRepository('OjsAdminBundle:SystemSetting')
            ->findOneBy(['name' => 'article_submission']);

        if ($permissionSetting && !$permissionSetting->getValue()) {
            return true;
        }

        return false;
    }

    private function respondAsNotAllowed()
    {
        return $this->render(
            'OjsSiteBundle:Site:not_available.html.twig',
            [
                'title' => 'title.submission_new',
                'message' => 'message.submission_not_available'
            ]
        );
    }

    /**
     * @param Article $article
     * @param Journal $journal
     * @param $locales
     * @return FormInterface
     */
    private function createCreateForm(Article $article, Journal $journal, $locales, $defaultLocale)
    {
        $form = $this->createForm(
            new ArticleSubmissionType(),
            $article,
            array(
                'action' => $this->generateUrl(
                    'ojs_journal_submission_new',
                    array('journalId' => $journal->getId())
                ),
                'method' => 'POST',
                'locales' => $locales,
                'default_locale' => $defaultLocale,
                'citationTypes' => array_keys($this->container->getParameter('citation_types'))
            )
        )
            ->add('save', 'submit', array('label' => 'save', 'attr' => array('class' => 'btn-block')));

        return $form;
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, $id)
    {
        if ($this->submissionsNotAllowed()) {
            return $this->respondAsNotAllowed();
        }
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();

        /** @var User $user */
        $user = $this->getUser();
        if (!$journal) {
            $this->throw404IfNotFound($journal);
        }

        /** @var ArticleRepository $articleRepository */
        $articleRepository = $em->getRepository('OjsJournalBundle:Article');
        /** @var Article $article */
        $article = $articleRepository->findOneBy(
            array(
                'id' => $id,
                'submitterUser' => $user,
                'status' => -1
            )
        );
        $this->throw404IfNotFound($article);

        $locales = [];
        $submissionLangObjects = $journal->getLanguages();
        foreach ($submissionLangObjects as $submissionLangObject) {
            $locales[] = $submissionLangObject->getCode();
        }
        $defaultLocale = $journal->getMandatoryLang()->getCode();
        $article->setCurrentLocale($defaultLocale);

        $form = $this->createEditForm($article, $journal, $locales, $defaultLocale);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $k = 0;
            foreach ($article->getArticleAuthors() as $f_articleAuthor) {
                $f_articleAuthor->setAuthorOrder($k);
                $f_articleAuthor->setArticle($article);
                $k++;
            }
            $i = 0;
            foreach ($article->getCitations() as $f_citations) {
                $f_citations->setOrderNum($i);
                $i++;
            }

            foreach ($article->getArticleFiles() as $f_articleFile) {
                $f_articleFile->setVersion(0);
                $f_articleFile->setArticle($article);
            }

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute(
                'ojs_journal_submission_preview',
                array('journalId' => $journal->getId(), 'articleId' => $article->getId())
            );
        }

        return $this->render(
            'OjsJournalBundle:ArticleSubmission:edit.html.twig',
            array(
                'article' => $article,
                'journal' => $journal,
                'form' => $form->createView(),
            )
        );
    }

    private function createEditForm(Article $article, Journal $journal, $locales, $defaultLocale)
    {
        $form = $this->createForm(
            new ArticleSubmissionType(),
            $article,
            array(
                'action' => $this->generateUrl(
                    'ojs_journal_submission_edit',
                    array('journalId' => $journal->getId(), 'id' => $article->getId())
                ),
                'method' => 'POST',
                'locales' => $locales,
                'default_locale' => $defaultLocale,
                'citationTypes' => array_keys($this->container->getParameter('citation_types'))
            )
        )
            ->add('save', 'submit', array('label' => 'save', 'attr' => array('class' => 'btn-block')));

        return $form;
    }

    /**
     * @param Request $request
     * @param $articleId
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function previewAction(Request $request, $articleId)
    {
        if ($this->submissionsNotAllowed()) {
            return $this->respondAsNotAllowed();
        }
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        $dispatcher = $this->get('event_dispatcher');
        $session = $this->get('session');

        /** @var User $user */
        $user = $this->getUser();
        if (!$journal) {
            $this->throw404IfNotFound($journal);
        }

        /** @var ArticleRepository $articleRepository */
        $articleRepository = $em->getRepository('OjsJournalBundle:Article');
        /** @var Article $article */
        $article = $articleRepository->findOneBy(
            array(
                'id' => $articleId,
                'submitterUser' => $user,
                'status' => -1
            )
        );
        $this->throw404IfNotFound($article);

        $form = $this->createForm(new ArticlePreviewType(), $article, array(
            'action' => $this->generateUrl(
                'ojs_journal_submission_preview',
                array('journalId' => $journal->getId(), 'articleId' => $article->getId())
            ),
            'method' => 'POST'
        ))
        ->add('submit', 'submit', array('label' => 'article.submit', 'attr' => ['class' => 'btn-block']));
        $form->handleRequest($request);

        $validator = $this->get('validator');
        $draftErrors = $validator->validate($article, null, ['groups' => 'submission']);

        if($form->isValid() && count($draftErrors) == 0) {
            if($session->has('submissionFiles')) {
                $session->remove('submissionFiles');
            }
            $article->setStatus(0);
            $article->setSubmissionDate(new \DateTime());
            $em->persist($article);


            // Assign user to author journal role
            /** @var Role $role */
            $role = $em
                ->getRepository('OjsUserBundle:Role')
                ->findOneBy(['role' => 'ROLE_AUTHOR']);

            /** @var JournalUser $journalUser */
            $journalUser = $em->getRepository('OjsJournalBundle:JournalUser')->findOneBy(array(
                'user' => $user
            ));
            if(!$journalUser) {
                $journalUser = new JournalUser();
                $journalUser->setJournal($journal)
                    ->setUser($user);
            }
            $journalUser->addRole($role);
            $em->persist($journalUser);


            $em->flush();

            $response = $this->redirectToRoute('ojs_journal_submission_me', ['journalId' => $article->getJournal()->getId()]);

            try {
                $event = new ArticleSubmitEvent($article, $request);
                $dispatcher->dispatch(ArticleSubmitEvents::SUBMIT_AFTER, $event);

                if (null !== $event->getResponse()) {
                    return $event->getResponse();
                }
            }
            catch(\Exception $e){
                throw $e;
            }
            return $response;
        }
        return $this->render(
            'OjsJournalBundle:ArticleSubmission:preview.html.twig',
            array(
                'article' => $article,
                'journal' => $journal,
                'translations' => $article->getTranslations(),
                'fileTypes' => ArticleFileParams::$FILE_TYPES,
                'form' => $form->createView(),
                'draftErrors' => $draftErrors
            )
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function startAction(Request $request)
    {
        $em = $this->getDoctrine();
        if ($this->submissionsNotAllowed()) {
            return $this->respondAsNotAllowed();
        }
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $session = $this->get('session');

        if (!$journal) {
            $this->throw404IfNotFound($journal);
        }

        /** @var SubmissionChecklist[] $checkLists */
        $checkLists = [];
        $checkListsChoices = [];
        foreach ($journal->getSubmissionChecklist() as $checkList) {
            if(
                $checkList->getVisible()
                && ($checkList->getLocale() === $request->getLocale() || empty($checkList->getLocale()))
            ) {
                $checkLists[] = $checkList;
                $checkListsChoices[$checkList->getId()] = $checkList->getId();
            }
        }

        $entity = new ArticleSubmissionStart();
        $journalSubmissionFiles = $em->getRepository('OjsJournalBundle:JournalSubmissionFile')
	            ->findBy([
                    'visible' => true,
                    'locale' => $request->getLocale(),
                    'journal' => $journal
                ]);
        foreach($journalSubmissionFiles as $file){

            $fileEntity = new ArticleSubmissionFile();
            $entity->addArticleSubmissionFile($fileEntity);
        }
        $form = $this->createStartForm($checkListsChoices, $entity);
        $form->handleRequest($request);

        $submissionFiles = [];
        if($form->isValid() && $form->isSubmitted()){
            foreach ($entity->getArticleSubmissionFiles() as $fileKey => $submissionFile) {
                if(empty($submissionFile->getFile()) && $journalSubmissionFiles[$fileKey]->getRequired()){
                    return $this->render(
                        'OjsJournalBundle:ArticleSubmission:start.html.twig',
                        array(
                            'journal' => $journal,
                            'checkLists' => $checkLists,
                            'journalSubmissionFiles' => $journalSubmissionFiles,
                            'form' => $form->createView()
                        )
                    );
                }
                $submissionFiles[$fileKey] = $submissionFile->getFile();
            }
            $session->set('submissionFiles', $submissionFiles);
            return $this->redirectToRoute('ojs_journal_submission_new', array('journalId' => $journal->getId()));
        }

        return $this->render(
            'OjsJournalBundle:ArticleSubmission:start.html.twig',
            array(
                'journal' => $journal,
                'checkLists' => $checkLists,
                'journalSubmissionFiles' => $journalSubmissionFiles,
                'form' => $form->createView()
            )
        );
    }

    /**
     * @param Array $checkListsChoices
     * @return FormInterface
     */
    private function createStartForm(array $checkListsChoices, $entity)
    {
        $form = $this->createForm(
            new ArticleStartType(),
            $entity,
            array(
                'checkListsChoices' => $checkListsChoices,
                'method' => 'POST'
            )
        )
            ->add('save', 'submit', array('label' => 'save.next', 'attr' => array('class' => 'btn-block')));

        return $form;
    }

    /**
     * Returns requested orcid user profile details
     * @param  Request      $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function getOrcidAuthorAction(Request $request)
    {
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
     * @return RedirectResponses
     * @throws NotFoundHttpException
     */
    public function cancelAction($id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        /** @var Article $article */
        $article = $em->getRepository('OjsJournalBundle:Article')->findOneBy(array(
            'submitterUser' => $this->getUser(),
            'id' => $id,
            'status' => -1
        ));

        $this->throw404IfNotFound($article);
        $em->remove($article);
        $em->flush();
        $this->addFlash('success', $this->get('translator')->trans('deleted'));

        return $this->redirectToRoute('ojs_user_index');
    }
}
