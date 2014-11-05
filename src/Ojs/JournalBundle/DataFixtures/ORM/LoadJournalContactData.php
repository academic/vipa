<?php

namespace Ojs\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojs\JournalBundle\Entity\JournalContact;

class LoadJournalContactData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * run after contact, contacttype and journal datafixtures executed
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $jcontact = new JournalContact();

        // get first journal record
        $journal = $manager->createQuery('SELECT c FROM OjsJournalBundle:Journal c')
                        ->setMaxResults(1)->getResult();
        // get first contacttype record
        $contactType = $manager->createQuery('SELECT c FROM OjsJournalBundle:ContactTypes c ')
                        ->setMaxResults(1)->getResult();
        //get first contact record
        $contact = $manager->createQuery('SELECT c FROM OjsJournalBundle:Contact c')
                        ->setMaxResults(1)->getResult();

        $jcontact->setJournal($journal[0]);
        $jcontact->setContactType($contactType[0]);
        $jcontact->setContact($contact[0]);
        $manager->persist($jcontact);

        $manager->flush();
    }

    public function getOrder()
    {
        return 9;
    }

}
