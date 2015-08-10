<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\Common\Params\ArticleFileParams;
use Ojs\Common\Params\ArticleParams;
use Ojs\Common\Services\GridAction;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\ArticleAuthor;
use Ojs\JournalBundle\Entity\ArticleFile;
use Ojs\JournalBundle\Entity\ArticleRepository;
use Ojs\JournalBundle\Entity\Author;
use Ojs\JournalBundle\Entity\Citation;
use Ojs\JournalBundle\Entity\Journal;
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
     * @param  bool     $all
     * @return Response
     */
    public function indexAction($all = false)
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
                $qb->andWhere($source1TableAlias.'.journal = :journal')
                    ->andWhere($source1TableAlias.'.status IN (:notDraftStatuses)')
                    ->setParameter('journal', $currentJournal)
                    ->setParameter('notDraftStatuses', array(-3, -2, 0 ,1));
                if(!$all){
                    $qb->andWhere($source1TableAlias.'.submitterId = :userId')
                        ->setParameter('userId', $user->getId());
                }
                return $qb;
            }
        );

        $source2->manipulateQuery(
            function (QueryBuilder $qb) use ($source2TableAlias, $user, $currentJournal, $all) {
                $qb->andWhere($source2TableAlias.'.journal = :journal')
                    ->andWhere($source2TableAlias.'.status = :status')
                    ->setParameter('journal', $currentJournal)
                    ->setParameter('status', -1);
                if(!$all){
                    $qb->andWhere($source2TableAlias.'.submitterId = :userId')
                        ->setParameter('userId', $user->getId());
                }
            }
        );

        $gridManager = $this->get('grid.manager');
        $submissionsGrid = $gridManager->createGrid('submission');
        $drafts = $gridManager->createGrid('drafts');
        $source1->manipulateRow(
            function (Row $row) use ($translator) {
                $statusText = ArticleParams::statusText($row->getField('status'));
                if (!is_array($statusText)) {
                    $row->setField('status', $translator->trans($statusText));
                } else {
                    $row->setField('status', $translator->trans('status.unknown'));
                }
                return $row;
            }
        );

        $source2->manipulateRow(
            function (Row $row) use ($translator) {
                $statusText = ArticleParams::statusText($row->getField('status'));
                if (!is_array($statusText)) {
                    $row->setField('status', $translator->trans($statusText));
                } else {
                    $row->setField('status', $translator->trans('status.unknown'));
                }
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

        if(!$session->has('competingFile')){
            return $this->redirectToRoute('ojs_journal_submission_start', array('journalId' => $journal->getId()));
        }

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
            ->setAddress($user->getAddress());

        $articleAuthor->setAuthor($author);
        $article
            ->setCompetingFile($session->get('competingFile'))
            ->setSubmitterId($user->getId())
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

        $form = $this->createCreateForm($article, $journal, $locales);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $k = 0;
            foreach ($article->getArticleAuthors() as $f_articleAuthor) {
                $f_articleAuthor->setAuthorOrder($k);
                $k++;
                if (empty($f_articleAuthor->getAuthor()->getLocale())) {
                    $f_articleAuthor->getAuthor()->setLocale($journal->getMandatoryLang()->getCode());
                }
            }
            $i = 0;
            foreach ($article->getCitations() as $f_citations) {
                $f_citations->setOrderNum($i);
                $f_citations->setLocale($journal->getMandatoryLang()->getCode());
                $i++;
            }
            foreach ($article->getArticleFiles() as $f_articleFile) {
                $f_articleFile->setArticle($article);
                $f_articleFile->setVersion(0);
            }
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute(
                'ojs_journal_submission_preview',
                array('journalId' => $journal->getId(), 'articleId' => $article->getId())
            );
        }

        return $this->render(
            'OjsJournalBundle:ArticleSubmission:new.html.twig',
            array(
                'article' => $article,
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
    private function createCreateForm(Article $article, Journal $journal, $locales)
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
                'citationTypes' => array_keys($this->container->getParameter('citation_types'))
            )
        )
            ->add('save', 'submit', array('label' => 'save', 'attr' => array('class' => 'btn-block')));

        return $form;
    }
    private function createEditForm(Article $article, Journal $journal, $locales)
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
                'submitterId' => $user->getId(),
                'status' => -1
            )
        );
        $this->throw404IfNotFound($article);

        $locales = [];
        $submissionLangObjects = $journal->getLanguages();
        foreach ($submissionLangObjects as $submissionLangObject) {
            $locales[] = $submissionLangObject->getCode();
        }

        $form = $this->createEditForm($article, $journal, $locales);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $k = 0;
            foreach ($article->getArticleAuthors() as $f_articleAuthor) {
                $f_articleAuthor->setAuthorOrder($k);
                $k++;
                if (empty($f_articleAuthor->getAuthor()->getLocale())) {
                    $f_articleAuthor->getAuthor()->setLocale($journal->getMandatoryLang()->getCode());
                }
            }
            $i = 0;
            foreach ($article->getCitations() as $f_citations) {
                $f_citations->setOrderNum($i);
                $f_citations->setLocale($journal->getMandatoryLang()->getCode());
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
                'form' => $form->createView(),
            )
        );
    }
    /**
     * @param Request $request
     * @param $articleId
     * @return RedirectResponse|Response
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
                'submitterId' => $user->getId(),
                'status' => -1
            )
        );
        $this->throw404IfNotFound($article);

        $translations = [];
        foreach ($article->getTranslations() as $translation) {
            $translations[$translation->getLocale()][$translation->getField()] = $translation->getContent();
        }

        $form = $this->createForm(new ArticlePreviewType(), $article, array(
            'action' => $this->generateUrl(
                'ojs_journal_submission_preview',
                array('journalId' => $journal->getId(), 'articleId' => $article->getId())
            ),
            'method' => 'POST'
        ))
        ->add('submit', 'submit', array('label' => 'article.submit'));
        $form->handleRequest($request);
        if($form->isValid()) {
            if($session->has('competingFile')) {
                $session->remove('competingFile');
            }
            $article->setStatus(0);
            $em->persist($article);


            // Assign user to author journal role
            /** @var Role $role */
            $role = $em
                ->getRepository('OjsUserBundle:Role')
                ->findOneBy(['role' => 'ROLE_AUTHOR']);

            /** @var JournalUser $journalUser */
            $journalUser = $em->getRepository('OjsJournalBundle:JournalUser')->findOneBy(array(
                'journal' => $journal, 'user' => $user
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

            }
            return $response;
        }
        return $this->render(
            'OjsJournalBundle:ArticleSubmission:preview.html.twig',
            array(
                'article' => $article,
                'translations' => $translations,
                'fileTypes' => ArticleFileParams::$FILE_TYPES,
                'form' => $form->createView()
            )
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function startAction(Request $request)
    {
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

        $form = $this->createStartForm($checkListsChoices);
        $form->handleRequest($request);
        if($form->isValid()){
            $session->set('competingFile', $form->getData()['competingFile']);
            return $this->redirectToRoute('ojs_journal_submission_new', array('journalId' => $journal->getId()));
        }

        return $this->render(
            'OjsJournalBundle:ArticleSubmission:start.html.twig',
            array(
                'journal' => $journal,
                'checkLists' => $checkLists,
                'form' => $form->createView()
            )
        );
    }

    /**
     * @param Array $checkListsChoices
     * @return FormInterface
     */
    private function createStartForm(array $checkListsChoices)
    {
        $form = $this->createForm(
            new ArticleStartType(),
            null,
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
     * @return RedirectResponse
     * @throws NotFoundHttpException
     */
    public function cancelAction($id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        /** @var Article $article */
        $article = $em->getRepository('OjsJournalBundle:Article')->findOneBy(array(
            'journal' => $journal,
            'submitterId' => $this->getUser()->getId(),
            'id' => $id,
            'status' => -1
        ));

        $this->throw404IfNotFound($article);
        $em->remove($article);
        $em->flush();
        $this->addFlash('success', $this->get('translator')->trans('deleted'));

        return $this->redirectToRoute('ojs_journal_submission_me', ['journalId' => $journal->getId()]);
    }
}
