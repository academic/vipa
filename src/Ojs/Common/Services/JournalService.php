<?php

namespace Ojs\Common\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalRepository;
use Ojs\JournalBundle\Entity\JournalUser;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;
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
        Session $session,
        Router $router,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack,
        $defaultInstitutionSlug
    )
    {
        $this->session = $session;
        $this->em = $em;
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

        if (!$journalId) {
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
     * @return bool|Journal
     */
    public function getJournalLocales(Journal $journal = null)
    {
        $journal = $this->getSelectedJournal();
        $submissionLangObjects = $journal->getLanguages();
        $locales = [];
        foreach ($submissionLangObjects as $submissionLangObject) {
            $locales[] = $submissionLangObject->getCode();
        }
        return $locales;
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


        return null;
    }

    /**
     * @param  Journal $journal
     * @return array
     * @todo implement
     */
    public function journalsArticlesStats(Journal $journal)
    {
        $stats = [];

        if (!$stats) {
            return [
                [
                    'id' => 0,
                    'hit' => 0,
                    'title' => null
                ]
            ];
        }
        return $stats;
    }

    public function getArticlesDownloadStats(Journal $journal)
    {
        $stats = [];
        /*$qb = $this->em->createQueryBuilder();
        $qb->select('article')
            ->from('OjsJournalBundle:Article', 'article')
            ->where(
                $qb->expr()->eq('article.journal', ':journal')
            )
            ->join('article.journal', 'with')
            ->setParameter('journal', $journal);
        $articles = $qb->getQuery()->getResult();
        foreach ($articles as $article) {


        }*/
        return $stats;
    }

    public function getArticleStats($id, $journal)
    {


        return null;
    }
}
