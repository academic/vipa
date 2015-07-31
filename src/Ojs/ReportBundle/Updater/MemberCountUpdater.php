<?php
/**
 * www
 */
namespace Ojs\ReportBundle\Updater;

use Ojs\JournalBundle\Entity\JournalUser;
use Ojs\JournalBundle\Entity\JournalUserRepository;

class MemberCountUpdater extends Updater implements UpdaterInterface
{
    public function update()
    {
        // TODO: Implement update() method.
    }

    /**
     * @return array
     */
    public function count()
    {
        /** @var JournalUserRepository $journalUserRepo */
        /** @var JournalUser[] $journalUsers */

        $role = $this->em->getRepository('OjsUserBundle:Role')->findOneBy(['role' => 'ROLE_AUTHOR']);
        $journalUserRepo = $this->em->getRepository('OjsJournalBundle:JournalUser');
        $journalUsers = $journalUserRepo->findByRoles($role);
        $result = [];

        foreach ($journalUsers as $journalUser) {
            $result[$journalUser->getJournal()->getId()][] = $journalUser->getUser()->getId();
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getObject()
    {
        return 'Ojs\JournalBundle\Entity\Journal';
    }
}