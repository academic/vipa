<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Contact;

class LoadContactData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $contact = new Contact();
        $contact->setAddress("Demo address");
        $contact->setEmail("contact@demo.com");
        $contact->setFirstName("Democontact");
        $contact->setLastName("Demo");
        $contact->setPhone("+90 505 729 8834");
        $manager->persist($contact);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }

}
