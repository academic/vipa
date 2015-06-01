<?php

namespace Ojs\Common\Services;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManager;
use Ojs\AnalyticsBundle\Document\ObjectViews;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Common methods for journal
 */
class JournalService
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var Session
     */
    private $session;

    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @var Router
     */
    private $router;

    /** @var  string */
    private $defaultInstitutionSlug;

    /**
     * @param EntityManager   $em
     * @param DocumentManager $dm
     * @param Session         $session
     * @param Router          $router
     * @param string          $defaultInstitutionSlug
     */
    public function __construct(EntityManager $em, DocumentManager $dm, Session $session, Router $router, $defaultInstitutionSlug)
    {
        $this->session = $session;
        $this->em = $em;
        $this->dm = $dm;
        $this->router = $router;
        $this->defaultInstitutionSlug = $defaultInstitutionSlug;
    }

    /**
     * @param  bool              $redirect
     * @return bool|Journal|RedirectResponse
     */
    public function getSelectedJournal($redirect = true)
    {
        $selectedJournalId = $this->session->get("selectedJournalId");
        $selectedJournal = $selectedJournalId ? $this->em->getRepository('OjsJournalBundle:Journal')->find($selectedJournalId) : null;
        if ($selectedJournal) {
            return $selectedJournal;
        }
        if ($redirect) {
            return new RedirectResponse($this->router->generate('user_join_journal'));
        }

        return false;
    }

    /**
     * get default institution record
     * @return Institution
     */
    public function getDefaultInstitution()
    {
        $institution = $this->defaultInstitutionSlug ? $this->em->getRepository('OjsJournalBundle:Institution')
            ->findOneBy(array('slug' => $this->defaultInstitutionSlug)) : null;

        return $institution;
    }

    /**
     *
     * @param  Journal $journal
     * @return boolean
     */
    public function generateUrl($journal)
    {
        $institution = $journal->getInstitution();
        $institutionSlug = $institution ? $institution->getSlug() : $this->defaultInstitutionSlug;

        return $this->router
            ->generate('ojs_journal_index', array('slug' => $journal->getSlug(), 'institution' => $institutionSlug), Router::ABSOLUTE_URL);
    }

    public function setSelectedJournal($journalId)
    {
        $this->session->set('selectedJournalId', $journalId);

        return $journalId;
    }

    /**
     *
     * @param  integer $journalId
     * @param  integer $page
     * @param  integer $limit
     * @return mixed   user list
     */
    public function getUsers($journalId, $page, $limit)
    {
        $users = $this->em->getRepository('OjsUserBundle:UserJournalRole')
            ->createQueryBuilder('j')
            ->where('j.journalId = :id')
            ->setParameter('id', $journalId)
            //->orderBy('id', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult($page)
            ->getQuery()
            ->getResult();

        return $users;
    }

    /**
     * @param $journal
     * @return array
     */
    public function journalStats(Journal $journal)
    {
        $object_view = $this->dm->getRepository('OjsAnalyticsBundle:ObjectViews');
        $journal_stats = $object_view->findBy(['entity' => 'journal', 'objectId' => $journal->getId()]);
        $groupped_journal_stats = [];
        $counted_article_stats = [];
        foreach ($journal_stats as $js) {
            /** @var ObjectViews $js */
            $dateKey = $js->getLogDate()->format('d-M-Y');
            $groupped_journal_stats[$dateKey] = isset($groupped_journal_stats[$dateKey]) ? $groupped_journal_stats[$dateKey] + 1 : 1;
            foreach ($journal->getArticles() as $article) {
                $article_stats = $object_view->findBy(['entity' => 'article', 'objectId' => $article->getId()]);
                foreach ($article_stats as $article_stat) {
                    if ($article_stat->getLogDate()->format('d-M-Y') == $dateKey && !in_array($article_stat->getId(), $counted_article_stats)) {
                        $counted_article_stats[] = $article_stat->getId();
                        $groupped_journal_stats[$dateKey] = isset($groupped_journal_stats[$dateKey]) ? $groupped_journal_stats[$dateKey] + 1 : 1;
                    }
                }
            }
        }
        ksort($groupped_journal_stats);

        return $groupped_journal_stats;
    }

    public function journalsArticlesStats(Journal $journal)
    {
        $object_view = $this->dm->getRepository('OjsAnalyticsBundle:ObjectViews');
        $stats = [];
        $affetted_articles = [];
        foreach ($journal->getArticles() as $article) {
            $articleStats = $object_view->findBy(['entity' => 'article', 'objectId' => $article->getId()]);
            if (!$articleStats) {
                continue;
            }
            foreach ($articleStats as $stat) {
                $dateKey = $stat->getLogDate()->format("d-M-Y");
                $stats[$dateKey][$article->getId()] = [
                    'hit' => isset($stats[$dateKey][$article->getId()]['hit']) ? $stats[$dateKey][$article->getId()]['hit'] + 1 : 1,
                    'title' => $article->getTitle(),
                ];
            }
            $affetted_articles[] = ['id' => $article->getId(),'title' => $article->getTitle()];
        }
        foreach ($stats as $date => $stat) {
            foreach ($affetted_articles as $article) {
                if (!isset($stats[$date][$article['id']])) {
                    $stats[$date][$article['id']] = [
                        'hit' => 0,
                        'title' => $article['title'],
                    ];
                }
            }
        }
        ksort($stats);

        return [
            'stats' => $stats,
            'articles' => $affetted_articles,
        ];
    }
}
