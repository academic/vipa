<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\ContactTypes;

class LoadContactTypesData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $ctype = new ContactTypes();
        $ctype->setName("Technical Contact");
        $ctype->setDescription("In case of technical issues, first person to contact.");
        $manager->persist($ctype);

        $ctypeDemo = new ContactTypes();
        $ctypeDemo->setName("Demo Contact");
        $ctypeDemo->setDescription("Demo Contact just for demo");
        $manager->persist($ctypeDemo);

        $manager->flush();
    }

    public function getOrder() {
        return 3;
    }

}
