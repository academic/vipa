<?php
/**
 * www
 */
namespace Ojs\AnalyticsBundle\Updater;

use Ojs\JournalBundle\Entity\Issue;

class PublishedIssueCountUpdater extends Updater implements UpdaterInterface
{
    public function update()
    {
        // TODO: Implement update() method.
    }

    public function count()
    {
        $ie = $this->em->getRepository('OjsJournalBundle:Issue');
        $all = $ie->findBy(['published'=>true]);
        $issues = [];
        foreach ($all as $r) {
            /** @var Issue $r */
            if (isset($issues[$r->getJournalId()])
                and in_array($r->getId(), $issues[$r->getJournalId()])
            ) {
                continue;
            }
            $issues[$r->getJournalId()][] = $r->getId();
        }
        return $issues;
    }

    /**
     * @return string
     */
    public function getObject()
    {
        return 'Ojs\JournalBundle\Entity\Issue';
    }
}
