<?php

namespace Ojs\Common\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManager;
use Ojs\ReportBundle\Document\ObjectViews;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalRepository;
use Ojs\JournalBundle\Entity\JournalUser;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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

    /** @var RequestStack */
    private $requestStack;

    /** @var  string */
    private $defaultInstitutionSlug;

    /**
     * @param EntityManager $em
     * @param DocumentManager $dm
     * @param Session $session
     * @param Router $router
     * @param TokenStorageInterface $tokenStorage
     * @param RequestStack $requestStack
     * @param $defaultInstitutionSlug
     */
    public function __construct(
        EntityManager $em,
        DocumentManager $dm,
        Session $session,
        Router $router,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack,
        $defaultInstitutionSlug
    )
    {
        $this->session = $session;
        $this->em = $em;
        $this->dm = $dm;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
        $this->defaultInstitutionSlug = $defaultInstitutionSlug;
    }

    /**
     * @return Journal
     */
    public function getSelectedJournal()
    {
        $journalId = $this->requestStack->getCurrentRequest()->attributes->get('journalId');

        if(!$journalId) {
            return false;
        }

        $selectedJournal = $this->em->getRepository('OjsJournalBundle:Journal')->find($journalId);

        if (!$selectedJournal) {
            return false;
        }

        return $selectedJournal;
    }

    /**
     * @param  Journal $journal
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
     * @return Collection
     */
    public function getSelectedJournalRoles(Journal $journal = null)
    {
        $journal = $journal ? $journal : $this->getSelectedJournal();
        $token = $this->tokenStorage->getToken();

        if ($token instanceof AnonymousToken || (!$journal instanceof Journal)) {
            return array();
        }

        /** @var JournalUser $journalUser */
        $user = $token->getUser();
        $journalUserRepo = $this->em->getRepository('OjsJournalBundle:JournalUser');
        $journalUser = $journalUserRepo->findOneBy(['journal' => $journal, 'user' => $user]);

        if (!$journalUser) {
            return new ArrayCollection();
        }

        return $journalUser->getRoles();
    }

    /**
     * @param string $checkRoles
     * @param  Journal $journal
     * @return bool
     * @deprecated
     */
    public function hasJournalRole($checkRoles, Journal $journal = null)
    {
        $journalRoles = $this->getSelectedJournalRoles($journal)->toArray();

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
     * @return string
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
        $object_view = $this->dm->getRepository('OjsReportBundle:ObjectViews');
        $journal_stats = $object_view->findBy(['entity' => 'journal', 'objectId' =>$journal->getId()]);
        $group_by_day = [];
        foreach ($journal_stats as $stat) {
            $group_by_day[$stat->getLogDate()->format('Y-m-d')]=
                isset($group_by_day[$stat->getLogDate()->format('Y-m-d')])?$group_by_day[$stat->getLogDate()->format('Y-m-d')]+1:1;
        }

        return $group_by_day;
    }

    /**
     * @param  Journal $journal
     * @return array
     */
    public function journalsArticlesStats(Journal $journal)
    {
        $object_view = $this->dm->getRepository('OjsReportBundle:ObjectView');
        $stats = [];
        $qb = $this->em->createQueryBuilder();
        $qb->select('article')
            ->from('OjsJournalBundle:Article','article')
            ->where(
                $qb->expr()->eq('article.journal',':journal')
            )
            ->join('article.journal','with')
            ->setParameter('journal',$journal);
        $articles = $qb->getQuery()->getResult();
        foreach ($articles as $article) {
            /** @var  Article $article*/
            $articleStats = $object_view->findOneBy(['entity' => 'article', 'objectId' => "{$article->getId()}"]);
            if (!$articleStats) {
                continue;
            }
            $issue = $article->getIssue();
            $stats[$article->getId()] = [
                'id'=>$article->getId(),
                'hit' => $articleStats->getTotal(),
                'title' => $article->getTitle(),
            ];
            $issue && $stats[$article->getId()]['issue']= $issue->getVolume() . "-" .$issue->getYear() . "-".$issue->getNumber();

        }
        if(!$stats){
            return [
                [
                    'id'=>0,
                    'hit'=>0,
                    'title'=>null
                ]
            ];
        }
        return $stats;
    }

    public function getArticlesDownloadStats(Journal $journal)
    {
        $object_view = $this->dm->getRepository('OjsReportBundle:ObjectDownload');
        $stats = [];
        $qb = $this->em->createQueryBuilder();
        $qb->select('article')
            ->from('OjsJournalBundle:Article','article')
            ->where(
                $qb->expr()->eq('article.journal',':journal')
            )
            ->join('article.journal','with')
            ->setParameter('journal',$journal);
        $articles = $qb->getQuery()->getResult();
        foreach ($articles as $article) {
            /** @var  Article $article*/
            $articleStats = $object_view->findOneBy(['entity' => 'article', 'objectId' => "{$article->getId()}"]);
            if (!$articleStats) {
                continue;
            }
            $issue = $article->getIssue();
            $stats[$article->getId()] = [
                'id'=>$article->getId(),
                'download' => $articleStats->getTotal(),
                'title' => $article->getTitle(),
            ];
            $issue && $stats[$article->getId()]['issue']= $issue->getVolume() . "-" .$issue->getYear() . "-".$issue->getNumber();

        }
        return $stats;
    }

    public function getArticleStats($id,$journal)
    {
        $article = $this->em->find('OjsJournalBundle:Article',$id);
        if(!$article)
            throw new NotFoundHttpException("Article not found!");
        if($article->getJournalId()!=$journal->getId())
            throw new AccessDeniedException;

        $object_view = $this->dm->getRepository('OjsReportBundle:ObjectViews');
        $article_stats = $object_view->findBy(['entity' => 'article', 'objectId' =>(string)$article->getId()]);
        $group_by_day = [];
        foreach ($article_stats as $stat) {
            $group_by_day[$stat->getLogDate()->format('Y-m-d')]=
                isset($group_by_day[$stat->getLogDate()->format('Y-m-d')])?$group_by_day[$stat->getLogDate()->format('Y-m-d')]+1:1;
        }

        return $group_by_day;
    }
}
