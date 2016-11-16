<?php

namespace Ojs\AnalyticsBundle\Listener;

use Doctrine\ORM\EntityManager;
use Ojs\AnalyticsBundle\Entity\ArticleFileStatistic;
use Ojs\AnalyticsBundle\Entity\ArticleStatistic;
use Ojs\AnalyticsBundle\Entity\IssueFileStatistic;
use Ojs\AnalyticsBundle\Entity\IssueStatistic;
use Ojs\AnalyticsBundle\Entity\JournalStatistic;
use Ojs\SiteBundle\Event\DownloadArticleFileEvent;
use Ojs\SiteBundle\Event\DownloadIssueFileEvent;
use Ojs\SiteBundle\Event\SiteEvents;
use Ojs\SiteBundle\Event\ViewArticleEvent;
use Ojs\SiteBundle\Event\ViewIssueEvent;
use Ojs\SiteBundle\Event\ViewJournalEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AnalyticsSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * AnalyticsSubscriber constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, RequestStack $requestStack)
    {
        $this->em           = $em;
        $this->requestStack = $requestStack;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            SiteEvents::VIEW_ISSUE              => 'onIssueView',
            SiteEvents::VIEW_JOURNAL            => 'onJournalView',
            SiteEvents::VIEW_ARTICLE            => 'onArticleView',
            SiteEvents::DOWNLOAD_ISSUE_FILE     => 'onIssueFileDownload',
            SiteEvents::DOWNLOAD_ARTICLE_FILE   => 'onArticleFileDownload',
        );
    }

    public function onArticleView(ViewArticleEvent $event)
    {
        $article = $event->getArticle();
        $stat = $this->em
            ->getRepository('OjsAnalyticsBundle:ArticleStatistic')
            ->findOneBy(['date' => new \DateTime(), 'article' => $article]);

        if (!$stat) {
            $stat = new ArticleStatistic();
            $stat->setDate(new \DateTime());
            $stat->setArticle($article);
            $stat->setView(1);
        } else {
            $stat->setView($stat->getView() + 1);
        }

        $article->increaseViewCount();

        $this->em->persist($article);
        $this->em->persist($stat);
        $this->em->flush();
    }

    public function onIssueView(ViewIssueEvent $event)
    {
        $issue = $event->getIssue();
        $stat = $this->em
            ->getRepository('OjsAnalyticsBundle:IssueStatistic')
            ->findOneBy(['date' => new \DateTime(), 'issue' => $issue]);

        if (!$stat) {
            $stat = new IssueStatistic();
            $stat->setDate(new \DateTime());
            $stat->setIssue($issue);
            $stat->setView(1);
        } else {
            $stat->setView($stat->getView() + 1);
        }

        $issue->increaseViewCount();

        $this->em->persist($issue);
        $this->em->persist($stat);
        $this->em->flush();
    }

    public function onJournalView(ViewJournalEvent $event)
    {
        $journal = $event->getJournal();
        $stat = $this->em
            ->getRepository('OjsAnalyticsBundle:JournalStatistic')
            ->findOneBy(['date' => new \DateTime(), 'journal' => $journal]);

        if (!$stat) {
            $stat = new JournalStatistic();
            $stat->setDate(new \DateTime());
            $stat->setJournal($journal);
            $stat->setView(1);
        } else {
            $stat->setView($stat->getView() + 1);
        }

        $journal->increaseViewCount();

        $this->em->persist($journal);
        $this->em->persist($stat);
        $this->em->flush();
    }

    public function onArticleFileDownload(DownloadArticleFileEvent $event)
    {
        $request = $this->requestStack->getMasterRequest();
        $session = $request->getSession();
        $articleFile = $event->getArticleFile();
        $sessionKey = 'download_article_file_'.$articleFile->getId();
        if($session->has($sessionKey)){
            return;
        }else{
            $session->set($sessionKey, 1);
        }
        $article = $event->getArticleFile()->getArticle();
        $journal = $article->getJournal();
        $stat = $this->em
            ->getRepository('OjsAnalyticsBundle:ArticleFileStatistic')
            ->findOneBy(['date' => new \DateTime(), 'articleFile' => $articleFile]);
        
        if (!$stat) {
            $stat = new ArticleFileStatistic();
            $stat->setDate(new \DateTime());
            $stat->setArticleFile($articleFile);
            $stat->setDownload(1);

        } else {
            $stat->setDownload($stat->getDownload() + 1);
        }

        $article->increaseDownloadCount();
        $journal->increaseDownloadCount();

        $this->em->persist($journal);
        $this->em->persist($stat);
        $this->em->flush();
    }

    public function onIssueFileDownload(DownloadIssueFileEvent $event)
    {
        $request = $this->requestStack->getMasterRequest();
        $session = $request->getSession();
        $issueFile = $event->getIssueFile();
        $sessionKey = 'download_issue_file_'.$issueFile->getId();
        if($session->has($sessionKey)){
            return;
        }else{
            $session->set($sessionKey, 1);
        }
        $issue = $issueFile->getIssue();
        $journal = $issue->getJournal();
        $stat = $this->em
            ->getRepository('OjsAnalyticsBundle:IssueFileStatistic')
            ->findOneBy(['date' => new \DateTime(), 'issueFile' => $issueFile]);

        if (!$stat) {
            $stat = new IssueFileStatistic();
            $stat->setDate(new \DateTime());
            $stat->setIssueFile($issueFile);
            $stat->setDownload(1);
        } else {
            $stat->setDownload($stat->getDownload() + 1);
        }

        $issue->increaseDownloadCount();
        $journal->increaseDownloadCount();

        $this->em->persist($journal);
        $this->em->persist($stat);
        $this->em->flush();
    }
}
