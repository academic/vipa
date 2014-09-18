<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Subject;

class LoadSubjectData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $sample = $this->getSampleData();
        foreach ($sample->subjects as $subjectName) {
            $subject = new Subject();
            $subject->setSubject($subjectName);
            $manager->persist($subject);
            $manager->flush();
        }
        // make reference to last subject
        $this->setReference('ref-subject', $subject);
    }

    public function getOrder()
    {
        return 6;
    }

    private function getSampleData()
    {
        return json_decode(file_get_contents(__DIR__ . '/subjects.json'));
    }

}
