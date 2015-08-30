<?php

namespace Ojs\SiteBundle\Controller;

use Doctrine\ORM\EntityManager;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalRepository;
use Ojs\SiteBundle\Entity\BlockRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Journal & Institution Hosting pages controller
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
        /** @var Institution $getInstitutionByDomain */
        $getInstitutionByDomain = $em->getRepository('OjsJournalBundle:Institution')->findOneByDomain($currentHost);
        if(!$getInstitutionByDomain){
            /** @var Journal $getJournalByDomain */
            $getJournalByDomain = $em->getRepository('OjsJournalBundle:Journal')->findOneByDomain($currentHost);
            if(!$getJournalByDomain){
                throw new NotFoundHttpException('This domain does not exist on this system');
            }
            return $this->journalIndexAction($request, $getJournalByDomain->getSlug(), true);
        }
        /** @var Journal $journal */
        foreach($getInstitutionByDomain->getJournals() as $journal){
            $journalPublicURI = $this->generateUrl('institution_hosting_journal_index', [
                'slug' => $journal->getSlug()
            ],true);
            $journal->setPublicURI($journalPublicURI);
        }
        return $this->render('OjsSiteBundle::Institution/institution_index.html.twig', [
            'entity' => $getInstitutionByDomain
        ]);
    }

    /**
     * @param Request $request
     * @param string $slug
     * @param bool $isJournalHosting
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function journalIndexAction(Request $request, $slug, $isJournalHosting = false)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var JournalRepository $journalRepo */
        $journalRepo = $em->getRepository('OjsJournalBundle:Journal');
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsSiteBundle:Block');
        /** @var Journal $journal */
        $journal = $journalRepo->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);
        $data['last_issue'] = $this->setupArticleURIs($journalRepo->getLastIssueId($journal), $isJournalHosting);
        $data['years'] = $this->setupIssueURIsByYear($journalRepo->getIssuesByYear($journal), $isJournalHosting);
        $data['journal'] = $journal;
        $data['page'] = 'journal';
        $data['blocks'] = $blockRepo->journalBlocks($journal);
        if($isJournalHosting){
            $journal->setPublicURI($this->generateUrl('journal_institution_hosting', [], true));
            $data['archive_uri'] = $this->generateUrl('journal_hosting_archive', [], true);
        }else{
            $journal->setPublicURI($this->generateUrl('institution_hosting_journal_index', [
                'slug', $journal->getSlug()
            ], true)
            );
            $data['archive_uri'] = $this->generateUrl('institution_hosting_journal_archive', [], true);
        }

        return $this->render('OjsSiteBundle::Journal/journal_index.html.twig', $data);
    }

    /**
     * @param $years
     * @param $isJournalHosting
     * @return mixed
     */
    private function setupIssueURIsByYear($years, $isJournalHosting)
    {
        foreach($years as $year){
            /** @var Issue $issue */
            foreach($year as $issue){
                if($isJournalHosting){
                    $issue->setPublicURI($this->generateUrl('journal_hosting_issue', [
                        'id' => $issue->getId()
                    ],true));
                }else{
                    $issue->setPublicURI($this->generateUrl('institution_hosting_journal_issue', [
                        'journal_slug' => $issue->getJournal()->getSlug(),
                        'id' => $issue->getId()
                    ],true));
                }
            }
        }
        return $years;
    }

    /**
     * @param Issue $last_issue
     * @param $isJournalHosting
     * @return mixed
     */
    private function setupArticleURIs($last_issue, $isJournalHosting)
    {
        /** @var Article $article */
        foreach($last_issue->getArticles() as $article){
            if($isJournalHosting){
                $article->setPublicURI($this->generateUrl('journal_hosting_issue_article',[
                    'issue_id' => $article->getIssue()->getId(),
                    'article_id' => $article->getId(),
                    ],true)
                );
            }else{
                $article->setPublicURI($this->generateUrl('institution_hosting_journal_issue_article',[
                    'slug' => $article->getIssue()->getJournal()->getSlug(),
                    'issue_id' => $article->getIssue()->getId(),
                    'article_id' => $article->getId(),
                ],true)
                );
            }
        }
        return $last_issue;
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
        $blockRepo = $em->getRepository('OjsSiteBundle:Block');
        /** @var Issue $issue */
        $issue = $issueRepo->find($id);
        $data['issue'] = $issue;
        $data['blocks'] = $blockRepo->journalBlocks($issue->getJournal());
        if($isJournalHosting){
            $data['isJournalHosting'] = true;
        }else{
            $data['isInstitutionHosting'] = true;
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
        if (!$data['article']) {
            throw $this->createNotFoundException($this->get('translator')->trans('Article Not Found'));
        }
        //log article view event
        $data['schemaMetaTag'] = '<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />';
        $data['meta'] = $this->get('ojs.article_service')->generateMetaTags($data['article']);
        $data['journal'] = $data['article']->getJournal();
        $data['page'] = 'journals';
        $data['blocks'] = $em->getRepository('OjsSiteBundle:Block')->journalBlocks($data['journal']);
        if($isJournalHosting){
            $data['journal']->setPublicURI($this->generateUrl('institution_hosting_journal_index',[], true));
            $data['archive_uri'] = $this->generateUrl('journal_hosting_archive', [], true);
        }else{
            $data['journal']->setPublicURI($this->generateUrl('institution_hosting_journal_index',[
                'slug' => $data['article']->getJournal()->getSlug()
            ], true));
            $data['archive_uri'] = $this->generateUrl('institution_hosting_journal_archive', [
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
        $blockRepo = $em->getRepository('OjsSiteBundle:Block');
        /** @var Journal $journal */
        if(is_null($slug)){
            $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneByDomain($currentHost);
        }else{
            $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        }
        $this->throw404IfNotFound($journal);

        /** @var Issue[] $issues */
        $issues = $em->getRepository('OjsJournalBundle:Issue')->findBy(
            array('journalId' => $journal->getId())
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
            $data['isInstitutionHosting'] = true;
        }

        return $this->render('OjsSiteBundle::Journal/archive_index.html.twig', $data);
    }
}
