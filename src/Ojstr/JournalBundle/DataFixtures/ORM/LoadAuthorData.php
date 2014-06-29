<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Author;

class LoadAuthorData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $author = new Author();
        $author->setAddress("Author demo address");
        $author->setEmail("author@demo.com");
        $author->setFirstName("Demo");
        $author->setInitials("DA");
        $author->setLastName("Author");
        $author->setSummary("Demo author summary");
        $manager->persist($author);
        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }

}
