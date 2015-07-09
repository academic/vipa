<?php

namespace Ojs\Common\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\Institution;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Ojs\JournalBundle\Entity\JournalRepository;
use Ojs\SiteBundle\Entity\BlockRepository;

class DomainListener
{

    private $em;
    private $baseHost;
    private $templating;

    public function __construct(EntityManager $em, TwigEngine $templating, $baseHost)
    {
        $this->em = $em;
        $this->baseHost = $baseHost;
        $this->templating = $templating;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $currentHost = $request->getHttpHost();
        $hostNames = explode(".", $currentHost);
        $bottomHostName = $hostNames[count($hostNames)-2] . "." . $hostNames[count($hostNames)-1];
        if ($this->baseHost !== $bottomHostName) {
            /** @var Institution $getInstitutionByDomain */
            $getInstitutionByDomain = $this->em->getRepository('OjsJournalBundle:Institution')->findOneByDomain($currentHost);
            if(!$getInstitutionByDomain){
                /** @var Journal $getJournalByDomain */
                $getJournalByDomain = $this->em->getRepository('OjsJournalBundle:Journal')->findOneByDomain($currentHost);
                if(!$getJournalByDomain){
                    throw new NotFoundHttpException('This domain does not exist on this system');
                }
                $event->setResponse($this->renderJournalTemplate($getJournalByDomain));
            }else{
                $event->setResponse($this->templating->renderResponse('OjsSiteBundle::Institution/institution_index.html.twig', ['entity' => $getInstitutionByDomain]));
            }
        }
        return $event;
    }

    private function renderJournalTemplate(Journal $journal)
    {
        /** @var JournalRepository $journalRepo */
        $journalRepo = $this->em->getRepository('OjsJournalBundle:Journal');
        /** @var BlockRepository $blockRepo */
        $blockRepo = $this->em->getRepository('OjsSiteBundle:Block');
        $data['last_issue'] = $journalRepo->getLastIssueId($journal);
        $data['years'] = $journalRepo->getIssuesByYear($journal);
        $data['journal'] = $journal;
        $data['page'] = 'journal';
        $data['blocks'] = $blockRepo->journalBlocks($journal);

        return $this->templating->renderResponse('OjsSiteBundle::Journal/journal_index.html.twig', $data);
    }
}
