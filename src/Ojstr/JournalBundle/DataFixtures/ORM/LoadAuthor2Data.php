<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Author;

class LoadAuthor2Data extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $author2 = new Author();
        $author2->setAddress("Author2 demo address");
        $author2->setEmail("author2@demo.com");
        $author2->setFirstName("Demo2");
        $author2->setInitials("DAX");
        $author2->setLastName("Author2");
        $author2->setSummary("Demo2 author summary");
        $manager->persist($author2);
        $manager->flush();
        $this->addReference('ref-author-record2', $author2);
    }

    public function getOrder()
    {
        return 2;
    }

}
