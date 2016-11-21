<?php

namespace Ojs\SiteBundle\Controller;

use Doctrine\ORM\EntityManager;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\CoreBundle\Params\ArticleStatuses;
use Ojs\CoreBundle\Params\IssueDisplayModes;
use Ojs\CoreBundle\Params\JournalStatuses;
use Ojs\CoreBundle\Params\PublisherStatuses;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\BlockRepository;
use Ojs\JournalBundle\Entity\BoardMember;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\IssueRepository;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalRepository;
use Ojs\JournalBundle\Entity\Section;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;

class JournalController extends Controller
{
    /**
     * @param string $slug
     * @param boolean $isJournalHosting
     * @return Response
     */
    public function archiveIndexAction($slug, $isJournalHosting = false)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsJournalBundle:Block');
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);

        if($journal->getStatus() !== JournalStatuses::STATUS_PUBLISHED || $journal->getPublisher()->getStatus() !== PublisherStatuses::STATUS_COMPLETE ){
            $journal = null;
            $this->throw404IfNotFound($journal);
        }

        $data['groupedIssues'] = $em->getRepository(Issue::class)->getByYear($journal, true);
        $data['page'] = 'archive';
        $data['blocks'] = $blockRepo->journalBlocks($journal);
        $data['journal'] = $journal;
        $data['isJournalHosting'] = $isJournalHosting;
        $data['displayModes'] = [
            'all' => IssueDisplayModes::SHOW_ALL,
            'title' => IssueDisplayModes::SHOW_TITLE,
            'volumeAndNumber' => IssueDisplayModes::SHOW_VOLUME_AND_NUMBER
        ];

        return $this->render('OjsSiteBundle::Journal/archive_index.html.twig', $data);
    }

    /**
     * @param string $slug
     * @param boolean $isJournalHosting
     * @return Response
     */
    public function journalBoardAction($slug, $isJournalHosting = false)
    {
        /**
         * @var Journal $journal
         * @var EntityManager $em
         * @var BlockRepository $blockRepo
         */
        $em = $this->getDoctrine()->getManager();
        $blockRepo = $em->getRepository('OjsJournalBundle:Block');

        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $boards = $journal->getBoards();

        $this->throw404IfNotFound($journal);

        if($journal->getStatus() !== JournalStatuses::STATUS_PUBLISHED || $journal->getPublisher()->getStatus() !== PublisherStatuses::STATUS_COMPLETE ){
            $journal = null;
            $this->throw404IfNotFound($journal);
        }

        $boardMembers = [];

        foreach ($boards as $board) {
            $boardMembers[$board->getId()] = $em
                ->getRepository(BoardMember::class)
                ->findBy(['board' => $board], ['seq' => 'ASC']);
        }

        $data = [
            'journal'       => $journal,
            'isJournalHosting' => $isJournalHosting,
            'page'          => 'journal',
            'board'         => $boards,
            'board_members' => $boardMembers,
            'blocks'        => $blockRepo->journalBlocks($journal),
        ];

        return $this->render('OjsSiteBundle::Journal/journal_board.html.twig', $data);
    }

    /**
     * @param $slug
     * @param boolean $isJournalHosting
     * @return Response
     */
    public function journalContactsAction($slug, $isJournalHosting = false)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);

        if($journal->getStatus() !== JournalStatuses::STATUS_PUBLISHED || $journal->getPublisher()->getStatus() !== PublisherStatuses::STATUS_COMPLETE ){
            $journal = null;
            $this->throw404IfNotFound($journal);
        }

        return $this->render("OjsSiteBundle:JournalContact:index.html.twig", [
            'contacts' => $em->getRepository("OjsJournalBundle:JournalContact")->findBy(['journal' => $journal], ['contactOrder' => 'ASC']),
            'blocks' => $em->getRepository('OjsJournalBundle:Block')->journalBlocks($journal),
            'journal' => $journal,
            'isJournalHosting' => $isJournalHosting
        ]);
    }

    /**
     * @param Request $request
     * @param $slug
     * @param $isJournalHosting boolean
     *
     * @return Response
     */
    public function journalIndexAction(Request $request, $slug, $isJournalHosting=false)
    {
        $session = $this->get('session');
        $journalService = $this->get('ojs.journal_service');
        $em = $this->getDoctrine()->getManager();
        /** @var JournalRepository $journalRepo */
        $journalRepo = $em->getRepository('OjsJournalBundle:Journal');
        /** @var \Ojs\JournalBundle\Entity\BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsJournalBundle:Block');
        /** @var IssueRepository $issueRepo */
        $issueRepo = $em->getRepository('OjsJournalBundle:Issue');
        /** @var Journal $journal */
        $journal = $journalRepo->findOneBy(['slug' => $slug, 'status' => JournalStatuses::STATUS_PUBLISHED]);
        $this->throw404IfNotFound($journal);

        $journalLocale = $journal->getMandatoryLang()->getCode();
        //if system supports journal mandatory locale set locale as journal mandatory locale

        if(0 && $journalLocale && in_array($journalLocale,$this->getParameter('locale_support'))){
            /**
             * if user is prefered a locale pass this logic then
             * @look for CommonController change locale function
             */
            if(!$session->has('_locale_prefered')){
                /**
                 * if session is fresh locale is not exists
                 * set journal locale and redirect to this action then
                 */
                if(!$session->has('_locale')){
                    $session->set('_locale', $journalLocale);

                    return $this->redirect($request->getRequestUri());
                }else{
                    /**
                     * if session is not fresh but session locale is
                     * not equal to journal locale set journal locale
                     * and redirect to this action then
                     */
                    if($session->get('_locale') !== $journalLocale){
                        $session->set('_locale', $journalLocale);

                        return $this->redirect($request->getRequestUri());
                    }
                }
            }
        }

        //if theme preview is active set given theme
        if(
            $request->query->has('themePreview') &&
            $request->query->has('id') &&
            is_int((int)$request->query->get('id')) &&
            $request->query->has('type')
        ){
            $previewThemeId = $request->query->get('id');
            $themeType = $request->query->get('type');
            if($themeType == 'journal'){
                $previewTheme = $em->getRepository('OjsJournalBundle:JournalTheme')->find($previewThemeId);
            }elseif($themeType == 'global'){
                $previewTheme = $em->getRepository('OjsAdminBundle:AdminJournalTheme')->find($previewThemeId);
            }
            $this->throw404IfNotFound($previewTheme);
            $journal->setTheme($previewTheme);
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('journal_view');

        $data['token'] = $token;
        $data['isJournalHosting'] = $isJournalHosting;
        $data['page'] = 'journal';
        $data['journal'] = $journal;
        $journal->setPublicURI($journalService->generateUrl($journal));
        $data['design'] = $journal->getDesign();
        $data['blocks'] = $blockRepo->journalBlocks($journal);

        /** @var Issue $lastIssue */
        $lastIssue = $issueRepo->findOneBy(['lastIssue' => true, 'journal' => $journal]);

        if ($lastIssue !== null) {
            $articles = [];

            /** @var Section $section */
            foreach ($lastIssue->getSections() as $section) {
                $articles = array_merge($articles, $em
                    ->getRepository(Article::class)
                    ->getOrderedArticles($lastIssue, $section)
                );
            }

            $data['lastIssueArticles'] = $this->setupArticleURIs($articles);
            $data['lastIssue'] = $lastIssue;
        } else {
            $data['lastIssueArticles'] = [];
            $data['lastIssue'] = null;
        }

        $data['posts'] = $em->getRepository('OjsJournalBundle:JournalPost')->findBy(['journal' => $journal]);
        $data['journalPages'] = $em->getRepository('OjsJournalBundle:JournalPage')->findBy(['journal' => $journal]);

        if($isJournalHosting){
            $data['years'] = $this->setupIssuesURIsByYear(array_slice($issueRepo->getByYear($journal), 0, 5, true),true);
            $data['archive_uri'] = $this->generateUrl(
                'journal_hosting_archive',
                [

                ],
                true
            );
        }else{
            $data['years'] = $this->setupIssuesURIsByYear(array_slice($issueRepo->getByYear($journal), 0, 5, true));
            $data['archive_uri'] = $this->generateUrl(
                'ojs_archive_index',
                [
                    'slug' => $journal->getSlug()
                ],
                true
            );
        }


        return $this->render('OjsSiteBundle::Journal/journal_index.html.twig', $data);
    }



    /**
     * @param $years
     * @param boolean $isJournalHosting
     * @return mixed
     */
    private function setupIssuesURIsByYear($years, $isJournalHosting = false)
    {
        foreach ($years as $year) {

            /** @var Issue $issue */
            foreach ($year as $issue) {
                if ($isJournalHosting) {
                    $issue->setPublicURI(
                        $this->generateUrl(
                            'journal_hosting_issue',
                            [
                                'id' => $issue->getId()
                            ],
                            true
                        )
                    );
                } else {
                    $issue->setPublicURI(
                        $this->generateUrl(
                            'ojs_issue_page',
                            [
                                'journal_slug' => $issue->getJournal()->getSlug(),
                                'id' => $issue->getId()
                            ],
                            true
                        )
                    );
                }
            }
        }

        return $years;
    }

    /**
     * @param array $articles
     * @param boolean $isJournalHosting
     * @return mixed
     */
    private function setupArticleURIs($articles = null, $isJournalHosting = false)
    {
        /** @var Article $article */
        foreach ($articles as $article) {
            if ($isJournalHosting) {
                $article->setPublicURI($this->generateUrl('journal_hosting_issue_article', [
                    'issue_id' => $article->getIssue()->getId(),
                    'article_id' => $article->getId(),
                ], true)
                );
            } else {
                $article->setPublicURI(
                    $this->generateUrl(
                        'ojs_article_page',
                        [
                            'slug' => $article->getIssue()->getJournal()->getSlug(),
                            'issue_id' => $article->getIssue()->getId(),
                            'article_id' => $article->getId(),
                        ]
                    )
                );
            }
        }

        return $articles;
    }

    /**
     * Also means last issue's articles
     *
     * @param $slug
     * @param boolean $isJournalHosting
     * @return Response
     */
    public function lastArticlesIndexAction($slug, $isJournalHosting = false)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsJournalBundle:Block');
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);

        if($journal->getStatus() !== JournalStatuses::STATUS_PUBLISHED || $journal->getPublisher()->getStatus() !== PublisherStatuses::STATUS_COMPLETE ){
            $journal = null;
            $this->throw404IfNotFound($journal);
        }

        $data['articles'] = $em->getRepository('OjsJournalBundle:Article')->findBy(
            array('journal' => $journal)
        );
        $data['page'] = 'articles';
        $data['blocks'] = $blockRepo->journalBlocks($journal);
        $data['journal'] = $journal;
        $data['isJournalHosting'] = $isJournalHosting;

        return $this->render('OjsSiteBundle::Journal/last_articles_index.html.twig', $data);
    }

    /**
     * @param Request $request
     * @param $slug
     * @param boolean $isJournalHosting
     * @return Response
     */
    public function subscribeAction(Request $request, $slug, $isJournalHosting = false)
    {
        $referer = $request->headers->get('referer');
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(array('slug' => $slug));
        $this->throw404IfNotFound($journal);

        if($journal->getStatus() !== JournalStatuses::STATUS_PUBLISHED || $journal->getPublisher()->getStatus() !== PublisherStatuses::STATUS_COMPLETE ){
            $journal = null;
            $this->throw404IfNotFound($journal);
        }


        $email = $request->get('mail');

        $emailConstraint = new EmailConstraint();
        $errors = $this->get('validator')->validateValue(
            $email,
            $emailConstraint
        );
        if (count($errors) > 0 || empty($email)) {
            $this->errorFlashBag('invalid.mail');

            return $this->redirect($referer);
        }

        $subscribeMail = new SubscribeMailList();
        $subscribeMail->setMail($email);
        $subscribeMail->setJournal($journal);
        $em->persist($subscribeMail);
        $em->flush();

        $this->successFlashBag('successfully.subscribed');

        return $this->redirect($referer);
    }

    /**
     * @param string $slug
     * @param boolean $isJournalHosting
     * @return Response
     */
    public function earlyPreviewIndexAction($slug, $isJournalHosting = false)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsJournalBundle:Block');
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);

        if($journal->getStatus() !== JournalStatuses::STATUS_PUBLISHED || $journal->getPublisher()->getStatus() !== PublisherStatuses::STATUS_COMPLETE ){
            $journal = null;
            $this->throw404IfNotFound($journal);
        }

        $articles = $em->getRepository(Article::class)->findBy(['journal' => $journal, 'status' => ArticleStatuses::STATUS_EARLY_PREVIEW]);

        $data = [
            'journal' => $journal,
            'isJournalHosting' => $isJournalHosting,
            'articles' => $articles,
            'page' => 'journal',
            'blocks' => $blockRepo->journalBlocks($journal),
        ];

        return $this->render('OjsSiteBundle::Article/journal_articles.html.twig', $data);

    }

}
