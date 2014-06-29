<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Subject;

class LoadSubjectData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $subject = new Subject();
        $subject->setSubject("Biology");
        $manager->persist($subject);
        $manager->flush();
    }

    public function getOrder() {
        return 6;
    }

}
