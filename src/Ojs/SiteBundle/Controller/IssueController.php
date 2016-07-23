<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\BlockRepository;
use Ojs\JournalBundle\Entity\IssueRepository;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\Article;
use Doctrine\Common\Collections\ArrayCollection;
use Ojs\CoreBundle\Params\IssueDisplayModes;
use Ojs\JournalBundle\Entity\Section;

class IssueController extends Controller
{
    public function issuePageAction($id)
    {
        /**
         * @var BlockRepository $blockRepo
         * @var IssueRepository $issueRepo
         * @var Issue $issue
         */
        $em = $this->getDoctrine()->getManager();

        $blockRepo = $em->getRepository('OjsJournalBundle:Block');
        $issueRepo = $em->getRepository('OjsJournalBundle:Issue');
        $articleRepo = $em->getRepository(Article::class);

        $issue = $issueRepo->find($id);
        $this->throw404IfNotFound($issue);

        $blocks = $blockRepo->journalBlocks($issue->getJournal());

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('issue_view');

        $sections = $this->setupIssueSections($issue);

        $articles = [];

        /** @var Section $section */
        foreach ($sections as $section) {
            $articles[$section->getId()] = $articleRepo->getOrderedArticles($issue, $section);
        }

        $displayModes = [
            'all' => IssueDisplayModes::SHOW_ALL,
            'title' => IssueDisplayModes::SHOW_TITLE,
            'volumeAndNumber' => IssueDisplayModes::SHOW_VOLUME_AND_NUMBER,
        ];

        return $this->render(
            'OjsSiteBundle:Issue:detail.html.twig',
            [
                'issue' => $issue,
                'blocks' => $blocks,
                'token' => $token,
                'sections' => $sections,
                'articles' => $articles,
                'displayModes' => $displayModes,
            ]
        );
    }

    /**
     * @param Issue $issue
     * @return ArrayCollection
     */
    private function setupIssueSections(Issue $issue)
    {
        $sections = [];
        foreach ($issue->getJournal()->getSections() as $section) {
            $sectionHaveIssueArticle = false;
            foreach ($section->getArticles() as $article) {
                if ($article->getIssue() !== null) {
                    if ($article->getIssue()->getId() == $issue->getId()) {
                        $sectionHaveIssueArticle = true;
                    }
                }
            }
            if ($sectionHaveIssueArticle) {
                $sections[] = $section;
            }
        }
        //order sections by section order
        uasort($sections, function ($a, $b) {
            return ((int)$a->getSectionOrder() > (int)$b->getSectionOrder()) ? 1 : -1;
        });
        return $sections;
    }
}
