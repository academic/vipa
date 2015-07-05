<?php

namespace Ojs\Common\Services;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManager;
use Ojs\AnalyticsBundle\Document\ObjectViews;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalRepository;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /** @var  string */
    private $defaultInstitutionSlug;

    /**
     * @param EntityManager         $em
     * @param DocumentManager       $dm
     * @param Session               $session
     * @param Router                $router
     * @param TokenStorageInterface $tokenStorage
     * @param $defaultInstitutionSlug
     */
    public function __construct(
        EntityManager $em,
        DocumentManager $dm,
        Session $session,
        Router $router,
        TokenStorageInterface $tokenStorage,
        $defaultInstitutionSlug
    ) {
        $this->session = $session;
        $this->em = $em;
        $this->dm = $dm;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
        $this->defaultInstitutionSlug = $defaultInstitutionSlug;
    }

    /**
     * @return Journal
     */
    public function getSelectedJournal()
    {
        $selectedJournalId = $this->session->get("selectedJournalId");
        $selectedJournal = $selectedJournalId ? $this->em->getRepository('OjsJournalBundle:Journal')->find(
            $selectedJournalId
        ) : false;
        if ($selectedJournal) {
            return $selectedJournal;
        } else {
            $selectedJournal = $this->setSelectedJournal();
        }
        if (!$selectedJournal instanceof Journal) {
            return false;
        }

        return $selectedJournal;
    }

    /**
     * @param  Journal      $journal
     * @return bool|Journal
     */
    public function setSelectedJournal(Journal $journal = null)
    {
        if ($journal) {
            $this->session->set('selectedJournalId', $journal->getId());

            return $journal;
        }
        $token = $this->tokenStorage->getToken();
        if ($token instanceof AnonymousToken) {
            return false;
        }
        $user = $token->getUser();
        // set first journal if found
        /** @var JournalRepository $journalRepo */
        $journalRepo = $this->em->getRepository('OjsJournalBundle:Journal');
        $journal = $journalRepo->findOneByUser($user);
        if (!$journal instanceof Journal) {
            return false;
        }
        $this->session->set('selectedJournalId', $journal->getId());

        return $journal;
    }

    /**
     * @param  Journal $journal
     * @return array
     */
    public function getSelectedJournalRoles(Journal $journal = null)
    {
        $journal = $journal ? $journal : $this->getSelectedJournal();
        $token = $this->tokenStorage->getToken();
        if ($token instanceof AnonymousToken || (!$journal instanceof Journal)) {
            return array();
        }
        $user = $token->getUser();
        $userJournalRoleRepo = $this->em->getRepository('OjsJournalBundle:JournalRole');
        $roles = $userJournalRoleRepo->findBy(['journal'=>$journal, 'user'=> $user]);

        return $roles;
    }

    /**
     * @param $checkRoles
     * @param  Journal $journal
     * @return bool
     * @deprecated
     */
    public function hasJournalRole($checkRoles, Journal $journal = null)
    {
        $journalRoles = $this->getSelectedJournalRoles($journal);

        if (is_array($checkRoles)) {
            foreach ($checkRoles as $role) {
                if (in_array($role, $journalRoles, true)) {
                    return true;
                }
            }

            return false;
        }

        return in_array($checkRoles, $journalRoles, true);
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
    public function generateUrl(Journal $journal)
    {
        $institution = $journal->getInstitution();
        $institutionSlug = $institution ? $institution->getSlug() : $this->defaultInstitutionSlug;

        return $this->router
            ->generate(
                'ojs_journal_index',
                array('slug' => $journal->getSlug(), 'institution' => $institutionSlug),
                Router::ABSOLUTE_URL
            );
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
        $users = $this->em->getRepository('OjsJournalBundle:JournalRole')
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
                    if ($article_stat->getLogDate()->format('d-M-Y') == $dateKey && !in_array(
                            $article_stat->getId(),
                            $counted_article_stats
                        )
                    ) {
                        $counted_article_stats[] = $article_stat->getId();
                        $groupped_journal_stats[$dateKey] = isset($groupped_journal_stats[$dateKey]) ? $groupped_journal_stats[$dateKey] + 1 : 1;
                    }
                }
            }
        }
        ksort($groupped_journal_stats);

        return $groupped_journal_stats;
    }

    /**
     * @param  Journal $journal
     * @return array
     */
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
                    'hit' => isset($stats[$dateKey][$article->getId()]['hit']) ? $stats[$dateKey][$article->getId(
                        )]['hit'] + 1 : 1,
                    'title' => $article->getTitle(),
                ];
            }
            $affetted_articles[] = ['id' => $article->getId(), 'title' => $article->getTitle()];
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
