<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Institution;

class LoadInstitutionData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $institution = new Institution();
        $institution->setAddress("Demo address");
        $institution->setEmail("abc@demo.edu.tr");
        $institution->setName("Abc University");
        $institution->setPhone("+90 312 555 5555");
        $manager->persist($institution);
        $manager->flush();
    }

    public function getOrder() {
        return 4;
    }

}
