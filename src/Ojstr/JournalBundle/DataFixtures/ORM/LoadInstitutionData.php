<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Institution;

class LoadInstitutionData implements FixtureInterface {

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
        return 1;
    }

}
