<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Subject;

class LoadSubjectData implements FixtureInterface {

    public function load(ObjectManager $manager) {
        $subject = new Subject();
        $subject->setSubject("Biology");
        $manager->persist($subject);
        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }

}
