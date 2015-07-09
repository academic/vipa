<?php

namespace Ojs\SiteBundle\Controller;

use Doctrine\ORM\EntityManager;
use Ojs\AnalyticsBundle\Document\ObjectDownloads;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\Common\Helper\FileHelper;
use Ojs\JournalBundle\Entity\File;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Entity\InstitutionRepository;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalRepository;
use Ojs\JournalBundle\Entity\SubjectRepository;
use Ojs\JournalBundle\Entity\Sums;
use Ojs\SiteBundle\Entity\BlockRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Yaml\Parser;

class HostingController extends Controller
{

    /**
     * @param Request $request
     * @return mixed
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
        return $this->render('OjsSiteBundle::Institution/institution_index.html.twig', [
            'entity' => $getInstitutionByDomain,
            'isInstitutionHosting' => true
        ]);
    }

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
        $data['last_issue'] = $journalRepo->getLastIssueId($journal);
        $data['years'] = $journalRepo->getIssuesByYear($journal);
        $data['journal'] = $journal;
        $data['page'] = 'journal';
        $data['blocks'] = $blockRepo->journalBlocks($journal);
        if($isJournalHosting){
            $data['isJournalHosting'] = true;
        }else{
            $data['isInstitutionHosting'] = true;
        }

        return $this->render('OjsSiteBundle::Journal/journal_index.html.twig', $data);
    }

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
            $data['isJournalHosting'] = true;
        }else{
            $data['isInstitutionHosting'] = true;
        }

        return $this->render('OjsSiteBundle:Article:article_page.html.twig', $data);
    }

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
