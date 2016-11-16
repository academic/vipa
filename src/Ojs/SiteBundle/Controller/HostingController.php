<?php

namespace Ojs\SiteBundle\Controller;

use Doctrine\ORM\EntityManager;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Block;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\IssueRepository;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalRepository;
use Ojs\JournalBundle\Entity\Publisher;
use Ojs\JournalBundle\Entity\BlockRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Journal & Publisher Hosting pages controller
 * Class HostingController
 * @package Ojs\SiteBundle\Controller
 */
class HostingController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $currentHost = $request->getHttpHost();

        $getJournalByDomain = $em->getRepository(Journal::class)->findOneBy(
            array('domain' => $currentHost)
        );
        $this->throw404IfNotFound($getJournalByDomain);

        return $this->journalIndexAction($getJournalByDomain->getSlug(), true);

    }

    /**
     * @param string $slug
     * @param bool $isJournalHosting
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function journalIndexAction($slug, $isJournalHosting = false)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var JournalRepository $journalRepo */
        $journalRepo = $em->getRepository('OjsJournalBundle:Journal');
        /** @var IssueRepository $issueRepo */
        $issueRepo = $em->getRepository(Issue::class);
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository(Block::class);
        /** @var Journal $journal */
        $journal = $journalRepo->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);
        $data['last_issue'] = $this->setupArticleURIs($issueRepo->getLastIssueByJournal($journal), $isJournalHosting);
        $data['years'] = $this->setupIssueURIsByYear($issueRepo->getLastIssueByJournal($journal), $isJournalHosting);
        $data['journal'] = $journal;
        $data['page'] = 'journal';
        $data['blocks'] = $blockRepo->journalBlocks($journal);
        if($isJournalHosting){
            $journal->setPublicURI($this->generateUrl('journal_publisher_hosting', [], true));
            $data['archive_uri'] = $this->generateUrl('journal_hosting_archive', [], true);
        }else{
            $journal->setPublicURI($this->generateUrl('publisher_hosting_journal_index', [
                'slug', $journal->getSlug()
            ], true)
            );
            $data['archive_uri'] = $this->generateUrl('publisher_hosting_journal_archive', [], true);
        }

        return $this->render('OjsSiteBundle::Journal/journal_index.html.twig', $data);
    }

    /**
     * @param Issue $lastIssue
     * @param boolean $isJournalHosting
     * @return Issue mixed
     */
    private function setupArticleURIs(Issue $lastIssue, $isJournalHosting)
    {
        foreach($lastIssue->getArticles() as $article){
            if($isJournalHosting){
                $article->setPublicURI($this->generateUrl('journal_hosting_issue_article',[
                    'issue_id' => $article->getIssue()->getId(),
                    'article_id' => $article->getId(),
                    ],true)
                );
            }else{
                $article->setPublicURI($this->generateUrl('publisher_hosting_journal_issue_article', [
                    'slug' => $article->getIssue()->getJournal()->getSlug(),
                    'issue_id' => $article->getIssue()->getId(),
                    'article_id' => $article->getId(),
                ],true)
                );
            }
        }

        return $lastIssue;
    }

    /**
     * @param Issue[][] $years
     * @param boolean $isJournalHosting
     * @return Issue[][]
     */
    private function setupIssueURIsByYear($years, $isJournalHosting)
    {
        foreach ($years as $year) {
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
                            'publisher_hosting_journal_issue',
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
     * @param $id
     * @param bool $isJournalHosting
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function issuePageAction($id, $isJournalHosting = false)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $issueRepo = $em->getRepository('OjsJournalBundle:Issue');
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsJournalBundle:Block');
        /** @var Issue $issue */
        $issue = $issueRepo->find($id);
        $this->throw404IfNotFound($issue);
        $data['issue'] = $issue;
        $data['blocks'] = $blockRepo->journalBlocks($issue->getJournal());
        if($isJournalHosting){
            $data['isJournalHosting'] = true;
        }else{
            $data['isPublisherHosting'] = true;
        }

        return $this->render('OjsSiteBundle:Issue:detail.html.twig', $data);
    }

    /**
     * @param null $slug
     * @param $article_id
     * @param null $issue_id
     * @param bool $isJournalHosting
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function articlePageAction($slug = null, $article_id, $issue_id = null, $isJournalHosting = false)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $entity Article */
        $data['article'] = $em->getRepository('OjsJournalBundle:Article')->find($article_id);
        $this->throw404IfNotFound($data['article']);

        //log article view event
        $data['schemaMetaTag'] = '<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />';
        $data['meta'] = $this->get('ojs.article_service')->generateMetaTags($data['article']);
        $data['journal'] = $data['article']->getJournal();
        $data['page'] = 'journals';
        $data['blocks'] = $em->getRepository('OjsJournalBundle:Block')->journalBlocks($data['journal']);
        if($isJournalHosting){
            $data['journal']->setPublicURI($this->generateUrl('publisher_hosting_journal_index', [], true));
            $data['archive_uri'] = $this->generateUrl('journal_hosting_archive', [], true);
        }else{
            $data['journal']->setPublicURI($this->generateUrl('publisher_hosting_journal_index', [
                'slug' => $data['article']->getJournal()->getSlug()
            ], true));
            $data['archive_uri'] = $this->generateUrl('publisher_hosting_journal_archive', [
                'slug' => $data['journal']->getSlug()
            ], true);
        }

        return $this->render('OjsSiteBundle:Article:article_page.html.twig', $data);
    }

    /**
     * @param Request $request
     * @param null $slug
     * @param bool $isJournalHosting
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function archiveIndexAction(Request $request, $slug = null, $isJournalHosting = false)
    {
        $em = $this->getDoctrine()->getManager();
        $currentHost = $request->getHttpHost();
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsJournalBundle:Block');
        /** @var Journal $journal */
        if(is_null($slug)){
            $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(
                array('domain' => $currentHost)
            );
        }else{
            $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        }
        $this->throw404IfNotFound($journal);

        /** @var Issue[] $issues */
        $issues = $em->getRepository('OjsJournalBundle:Issue')->findBy(
            array('journal' => $journal)
        );
        $groupedIssues = [];
        foreach ($issues as $issue) {
            $groupedIssues[$issue->getYear()][] = $issue;
        }
        krsort($groupedIssues);
        $data['groupedIssues'] = $groupedIssues;
        $data['page'] = 'archive';
        $data['blocks'] = $blockRepo->journalBlocks($journal);
        $data['journal'] = $journal;
        if($isJournalHosting){
            $data['isJournalHosting'] = true;
        }else{
            $data['isPublisherHosting'] = true;
        }

        return $this->render('OjsSiteBundle::Journal/archive_index.html.twig', $data);
    }
}
