<?php

namespace Ojs\CoreBundle\Security;

use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Security\UserProvider;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Service\JournalService;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;

class EmailUserProvider extends UserProvider
{
    private $journalService;

    /**
     * {@inheritDoc}
     */
    public function __construct(UserManagerInterface $userManager, JournalService $journalService)
    {
        $this->journalService = $journalService;
        parent::__construct($userManager);
    }

    /**
     * {@inheritDoc}
     */
    protected function findUser($username)
    {
        return $this->userManager->findUserByUsernameOrEmail($username);
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(SecurityUserInterface $user)
    {
        $reloadedUser = parent::refreshUser($user);
        //if current journal exists inject to user object
        $currentJournal = $this->journalService->getSelectedJournal();
        if($currentJournal instanceof Journal){
            $reloadedUser->setCurrentJournal($currentJournal);
        }

        return $reloadedUser;
    }
}
