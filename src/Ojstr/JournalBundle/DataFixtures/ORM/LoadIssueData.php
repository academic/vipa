<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Issue;

class LoadIssueData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $journal = $manager->createQuery('SELECT c FROM OjstrJournalBundle:Journal c')
                        ->setMaxResults(1)->getResult();
        $issue = new Issue();
        $issue->setDatePublished(new \DateTime());
        $issue->setJournal($journal[0]);
        $issue->setNumber(10);
        $issue->setTitle("Issue Title");
        $issue->setVolume(4);
        $issue->setYear(2);
        $manager->persist($issue);
        $manager->flush();
    }

    public function getOrder() {
        return 10;
    }

}
