<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\CoreBundle\Params\JournalStatuses;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalRepository;
use Ojs\JournalBundle\Entity\Block;
use Ojs\JournalBundle\Entity\BlockRepository;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\IssueRepository;
use Ojs\CoreBundle\Params\IssueDisplayModes;
use Doctrine\ORM\EntityManager;
use Ojs\JournalBundle\Entity\BoardMember;
use Ojs\JournalBundle\Entity\JournalContact;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ojs\JournalBundle\Entity\Section;
use Ojs\JournalBundle\Entity\Article;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;

class JournalController extends Controller
{
    public function archiveIndexAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsJournalBundle:Block');
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);

        $data['groupedIssues'] = $em->getRepository(Issue::class)->getByYear($journal, true);
        $data['page'] = 'archive';
        $data['blocks'] = $blockRepo->journalBlocks($journal);
        $data['journal'] = $journal;
        $data['displayModes'] = [
            'all' => IssueDisplayModes::SHOW_ALL,
            'title' => IssueDisplayModes::SHOW_TITLE,
            'volumeAndNumber' => IssueDisplayModes::SHOW_VOLUME_AND_NUMBER
        ];

        return $this->render('OjsSiteBundle::Journal/archive_index.html.twig', $data);
    }

    public function journalBoardAction($slug)
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
        $boardMembers = [];

        foreach ($boards as $board) {
            $boardMembers[$board->getId()] = $em
                ->getRepository(BoardMember::class)
                ->findBy(['board' => $board], ['seq' => 'ASC']);
        }

        $data = [
            'journal'       => $journal,
            'page'          => 'journal',
            'board'         => $boards,
            'board_members' => $boardMembers,
            'blocks'        => $blockRepo->journalBlocks($journal),
        ];

        return $this->render('OjsSiteBundle::Journal/journal_board.html.twig', $data);
    }

    public function journalContactsAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);

        return $this->render("OjsSiteBundle:JournalContact:index.html.twig", [
            'contacts' => $em->getRepository("OjsJournalBundle:JournalContact")->findBy(['journal' => $journal], ['contactOrder' => 'ASC']),
            'blocks' => $em->getRepository('OjsJournalBundle:Block')->journalBlocks($journal),
            'journal' => $journal,
        ]);
    }

    /**
     * @param Request $request
     * @param $publisher
     * @param $slug
     * @return Response
     */
    public function journalIndexAction(Request $request, $publisher, $slug)
    {
        $journalService = $this->get('ojs.journal_service');
        $em = $this->getDoctrine()->getManager();
        /** @var JournalRepository $journalRepo */
        $journalRepo = $em->getRepository('OjsJournalBundle:Journal');
        /** @var \Ojs\JournalBundle\Entity\BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsJournalBundle:Block');
        /** @var IssueRepository $issueRepo */
        $issueRepo = $em->getRepository('OjsJournalBundle:Issue');

        $publisherEntity = $em->getRepository('OjsJournalBundle:Publisher')->findOneBy(['slug' => $publisher]);
        $this->throw404IfNotFound($publisherEntity);

        /** @var Journal $journal */
        $journal = $journalRepo->findOneBy(['slug' => $slug, 'publisher' => $publisherEntity, 'status' => JournalStatuses::STATUS_PUBLISHED]);
        $this->throw404IfNotFound($journal);

        //if system supports journal mandatory locale set locale as journal mandatory locale
        if(in_array($journal->getMandatoryLang()->getCode(),$this->getParameter('locale_support'))){
            $request->setLocale($journal->getMandatoryLang()->getCode());
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
        $data['page'] = 'journal';
        $data['journal'] = $journal;
        $journal->setPublicURI($journalService->generateUrl($journal));
        $data['design'] = $journal->getDesign();
        $data['blocks'] = $blockRepo->journalBlocks($journal);
        $data['years'] = $this->setupIssuesURIsByYear(array_slice($issueRepo->getByYear($journal), 0, 5, true));

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

        $data['archive_uri'] = $this->generateUrl(
            'ojs_archive_index',
            [
                'slug' => $journal->getSlug(),
                'publisher' => $journal->getPublisher()->getSlug(),
            ],
            true
        );

        return $this->render('OjsSiteBundle::Journal/journal_index.html.twig', $data);
    }



    /**
     * @param $years
     * @return mixed
     */
    private function setupIssuesURIsByYear($years)
    {
        foreach ($years as $year) {
            /** @var Issue $issue */
            foreach ($year as $issue) {
                $issue->setPublicURI(
                    $this->generateUrl(
                        'ojs_issue_page',
                        [
                            'publisher' => $issue->getJournal()->getPublisher()->getSlug(),
                            'journal_slug' => $issue->getJournal()->getSlug(),
                            'id' => $issue->getId(),
                        ],
                        true
                    )
                );
            }
        }

        return $years;
    }

    /**
     * @param array $articles
     * @return mixed
     */
    private function setupArticleURIs($articles = null)
    {
        /** @var Article $article */
        foreach ($articles as $article) {
            $article->setPublicURI(
                $this->generateUrl(
                    'ojs_article_page',
                    [
                        'publisher'  => $article->getIssue()->getJournal()->getPublisher()->getSlug(),
                        'slug'       => $article->getIssue()->getJournal()->getSlug(),
                        'issue_id'   => $article->getIssue()->getId(),
                        'article_id' => $article->getId(),
                    ]
                )
            );
        }

        return $articles;
    }

    /**
     * Also means last issue's articles
     *
     * @param $slug
     * @return Response
     */
    public function lastArticlesIndexAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsJournalBundle:Block');
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);
        $data['articles'] = $em->getRepository('OjsJournalBundle:Article')->findBy(
            array('journal' => $journal)
        );
        $data['page'] = 'articles';
        $data['blocks'] = $blockRepo->journalBlocks($journal);
        $data['journal'] = $journal;

        return $this->render('OjsSiteBundle::Journal/last_articles_index.html.twig', $data);
    }

    /**
     * @param Request $request
     * @param $slug
     * @return Response
     */
    public function subscribeAction(Request $request, $slug)
    {
        $referer = $request->headers->get('referer');
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(array('slug' => $slug));
        $this->throw404IfNotFound($journal);
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
}
