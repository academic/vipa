<?php
/**
 * www
 */
namespace Ojs\AnalyticsBundle\Updater;

use Ojs\UserBundle\Entity\UserJournalRole;
use Ojs\UserBundle\Entity\UserRepository;

class UserCountUpdater extends Updater implements UpdaterInterface
{
    private $object;
    public function update()
    {
        // TODO: Implement update() method.
    }

    public function count()
    {
        /** @var UserRepository $ue */
        $ue = $this->em->getRepository('Ojs\UserBundle\Entity\UserJournalRole');
        $all = $ue->findAll();
        $journalUsers = [];
        foreach ($all as $r) {
            /** @var UserJournalRole $r */
            if (isset($journalUsers[$r->getJournalId()])
                and in_array($r->getUserId(), $journalUsers[$r->getJournalId()])
            ) {
                continue;
            }
            $journalUsers[$r->getJournalId()][] = $r->getUserId();
        }

        return $journalUsers;
    }

    /**
     * @param $id
     * @return string
     */
    public function getObject($id)
    {
        return "Ojs\JournalBundle\Entity\Journal";
    }
}
