<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\JournalSection;

class LoadJournalSectionData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $journal = $this->getReference('ref-journal');
        $section = new JournalSection();
        $section->setJournal($journal);
        $section->setTitle('Editorial');
        $section->setHideTitle(FALSE);
        $section->setAllowIndex(TRUE);
        $manager->persist($section);
        $this->addReference('ref-section', $section);
        $manager->flush();
    }

    public function getOrder() {
        return 16;
    }

}
