<?php

namespace Ojs\ReportBundle\Updater;

use Ojs\JournalBundle\Entity\JournalUser;
use Ojs\JournalBundle\Entity\JournalUserRepository;

class ReaderCountUpdater extends Updater implements UpdaterInterface
{
    public function update()
    {
        // TODO: Implement update() method.
    }

    public function count()
    {
        /** @var JournalUserRepository $journalUserRepo */
        /** @var JournalUser[] $journalUsers */

        $role = $this->em->getRepository('OjsUserBundle:Role')->findOneBy(['role' => 'ROLE_READER']);
        $journalUserRepo = $this->em->getRepository('OjsJournalBundle:JournalUser');
        $journalUsers = $journalUserRepo->findByRoles($role);
        $result = [];

        foreach ($journalUsers as $journalUser) {
            $result[$journalUser->getJournal()->getId()][] = $journalUser->getUser()->getId();
        }

        return $result;
    }

    public function getObject()
    {
        return 'Ojs\JournalBundle\Entity\Journal';
    }
}
