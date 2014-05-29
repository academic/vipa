<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\JournalContact;

class LoadJournalContactData implements FixtureInterface {

    /**
     * run after contact, contacttype and journal datafixtures executed
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager) {
        $jcontact = new JournalContact();

        // get first journal record       
        $journal = $em->getRepository('OjstrJournalBundle:Journal')->findOne();
        // get first contacttype record
        $contactType = $em->getRepository('OjstrJournalBundle:ContactType')->findOne();
        //get first contact record
        $contact = $em->getRepository('OjstrJournalBundle:Contact')->findOne();

        $jcontact->setJournal($journal);
        $jcontact->setContactType($contactType);
        $jcontact->setContact($contact);
        $manager->persist($jcontact);
        
        $manager->flush();
    }

    public function getOrder() {
        return 3;
    }

}
