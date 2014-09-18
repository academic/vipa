<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Issue;

class LoadIssueData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $journal = $this->getReference('ref-journal');
        $issue = new Issue();
        $issue->setDatePublished(new \DateTime());
        $issue->setNumber(10);
        $issue->setTitle("Issue Title");
        $issue->setVolume(4);
        $issue->setYear(2);
        $issue->setJournal($journal);
        $manager->persist($issue);
        $this->addReference('ref-issue', $issue);
        $manager->flush();
    }

    public function getOrder()
    {
        return 18;
    }

}
