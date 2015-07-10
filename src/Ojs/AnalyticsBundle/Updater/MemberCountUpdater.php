<?php
/**
 * www
 */
namespace Ojs\AnalyticsBundle\Updater;

use Documents\UserRepository;
use Ojs\JournalBundle\Entity\JournalRole;

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
        $readerRole = $this->em->getRepository("OjsUserBundle:Role")->findOneBy(['role' => 'ROLE_AUTHOR']);
        /** @var UserRepository $ue */
        $ue = $this->em->getRepository('Ojs\JournalBundle\Entity\JournalRole');
        $all = $ue->findBy(['roleId' => $readerRole->getId()]);
        $journalUsers = [];
        foreach ($all as $r) {
            /** @var JournalRole $r */
            if (isset($journalUsers[$r->getJournalId()])
                && in_array($r->getUserId(), $journalUsers[$r->getJournalId()])
            ) {
                continue;
            }
            $journalUsers[$r->getJournalId()][] = $r->getUserId();
        }
        return $journalUsers;
    }
    /**
     * @return string
     */
    public function getObject()
    {
        return 'Ojs\JournalBundle\Entity\Journal';
    }
}