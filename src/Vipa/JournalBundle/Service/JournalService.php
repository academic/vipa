<?php

namespace Vipa\JournalBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Vipa\JournalBundle\Entity\Journal;
use Vipa\JournalBundle\Entity\JournalUser;
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
    private $defaultPublisherSlug;

    /**
     * @param EntityManager $em
     * @param Session $session
     * @param Router $router
     * @param TokenStorageInterface $tokenStorage
     * @param RequestStack $requestStack
     * @param $defaultPublisherSlug
     */
    public function __construct(
        EntityManager $em,
        Session $session,
        Router $router,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack,
        $defaultPublisherSlug
    )
    {
        $this->session = $session;
        $this->em = $em;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
        $this->defaultPublisherSlug = $defaultPublisherSlug;
    }

    /**
     * @return array
     */
    public function getJournalLocales()
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
     * @param bool|true $useCache
     * @return bool|Journal
     */
    public function getSelectedJournal($useCache = true)
    {
        $request = $this->requestStack->getCurrentRequest();
        if(!$request) {
            return false;
        }
        $journalId = $request->attributes->get('journalId');
        if (!$journalId) {
            return false;
        }
        /** @var Journal $selectedJournal */
        $selectedJournal = $this->em->getRepository('VipaJournalBundle:Journal')->getById($journalId, $useCache);
        if (!$selectedJournal) {
            return false;
        }
        $this->em->getConfiguration()->setDefaultQueryHint('multiJournal', $selectedJournal->getId());
        return $selectedJournal;
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
        $journalUserRepo = $this->em->getRepository('VipaJournalBundle:JournalUser');
        $journalUser = $journalUserRepo->findOneBy(['journal' => $journal, 'user' => $user]);

        if (!$journalUser) {
            return new ArrayCollection();
        }

        return $journalUser->getRoles();
    }

    /**
     *
     * @param  Journal $journal
     * @return string
     */
    public function generateUrl(Journal $journal)
    {
        return $this->router
            ->generate(
                'vipa_journal_index',
                array('slug' => $journal->getSlug()),
                Router::ABSOLUTE_URL
            );
    }
}
