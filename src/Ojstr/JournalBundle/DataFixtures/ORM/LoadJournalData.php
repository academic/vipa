<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Journal;

class LoadJournalData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $journal = new Journal();
        $journal->setIssn("1300-7041");
        $journal->setMission("Mission text");
        $journal->setPeriod("2");
        $journal->setPublishStatus(0);
        $journal->setScope("Scope text");
        $journal->setSubtitle("Subtitle text");
        $journal->setTitle("Example Journal");
        $journal->setTitleAbbr("EXJ");
        $journal->setTitleTransliterated(NULL);
        $journal->setUrl("https://example.gov");
        $manager->persist($journal);
        $manager->flush();
    }

    public function getOrder() {
        return 8;
    }

}
