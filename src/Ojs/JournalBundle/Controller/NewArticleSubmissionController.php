<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Ojs\Common\Params\ArticleFileParams;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\ArticleAuthor;
use Ojs\JournalBundle\Entity\ArticleFile;
use Ojs\JournalBundle\Entity\ArticleRepository;
use Ojs\JournalBundle\Entity\Author;
use Ojs\JournalBundle\Entity\Citation;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\SubmissionChecklist;
use Ojs\JournalBundle\Event\ArticleSubmitEvent;
use Ojs\JournalBundle\Event\ArticleSubmitEvents;
use Ojs\JournalBundle\Form\Type\ArticlePreviewType;
use Ojs\JournalBundle\Form\Type\ArticleStartType;
use Ojs\JournalBundle\Form\Type\ArticleSubmissionType;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Article Submission controller.
 *
 */
class NewArticleSubmissionController extends Controller
{

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
            return $this->redirectToRoute('ojs_journal_new_submission_start', array('journalId' => $journal->getId()));
        }

        /** @var User $user */
        $user = $this->getUser();
        if (!$journal) {
            return $this->redirect($this->generateUrl('ojs_journal_user_register_list'));
        }

        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            return $this->redirect(
                $this->generateUrl('ojs_journal_submission_confirm', ['journalId' => $journal->getId()])
            );
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
                $f_articleFile->setVersion(0);
            }
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute(
                'ojs_journal_new_submission_preview',
                array('journalId' => $journal->getId(), 'articleId' => $article->getId())
            );
        }

        return $this->render(
            'OjsJournalBundle:NewArticleSubmission:new.html.twig',
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

    private function createCreateForm(Article $article, Journal $journal, $locales)
    {
        $form = $this->createForm(
            new ArticleSubmissionType(),
            $article,
            array(
                'action' => $this->generateUrl(
                    'ojs_journal_new_submission_new',
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
                    'ojs_journal_new_submission_edit',
                    array('journalId' => $journal->getId(), 'articleId' => $article->getId())
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
     * @param $articleId
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, $articleId)
    {
        if ($this->submissionsNotAllowed()) {
            return $this->respondAsNotAllowed();
        }
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();

        /** @var User $user */
        $user = $this->getUser();
        if (!$journal) {
            return $this->redirect($this->generateUrl('ojs_journal_user_register_list'));
        }

        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            return $this->redirect(
                $this->generateUrl('ojs_journal_submission_confirm', ['journalId' => $journal->getId()])
            );
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
            }
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute(
                'ojs_journal_new_submission_preview',
                array('journalId' => $journal->getId(), 'articleId' => $article->getId())
            );
        }

        return $this->render(
            'OjsJournalBundle:NewArticleSubmission:new.html.twig',
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
            return $this->redirect($this->generateUrl('ojs_journal_user_register_list'));
        }

        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            return $this->redirect(
                $this->generateUrl('ojs_journal_submission_confirm', ['journalId' => $journal->getId()])
            );
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
                'ojs_journal_new_submission_preview',
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
            'OjsJournalBundle:NewArticleSubmission:preview.html.twig',
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
            return $this->redirect($this->generateUrl('ojs_journal_user_register_list'));
        }

        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            return $this->redirect(
                $this->generateUrl('ojs_journal_submission_confirm', ['journalId' => $journal->getId()])
            );
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
            return $this->redirectToRoute('ojs_journal_new_submission_new', array('journalId' => $journal->getId()));
        }

        return $this->render(
            'OjsJournalBundle:NewArticleSubmission:start.html.twig',
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
}
