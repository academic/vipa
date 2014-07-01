<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Subject;

class LoadSubjectData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $subjects = array('Biology', 'Computer Science', 'Genetics');
        foreach ($subjects as $subjectName) {
            $subject = new Subject();
            $subject->setSubject($subjectName);
            $manager->persist($subject);
            $manager->flush();
        }
    }

    public function getOrder() {
        return 6;
    }

}
