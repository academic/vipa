<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\InstitutionTypes;

class LoadInstitutionTypesData implements FixtureInterface {

    public function load(ObjectManager $manager) {
        $itype = new InstitutionTypes();
        $itype->setName("University");
        $itype->setDescription("Demo intsitution desc.");
        $manager->persist($itype);


        $itype2 = new InstitutionTypes();
        $itype2->setName("Government");
        $itype2->setDescription("Demo intsitution desc.");
        $manager->persist($itype2);

        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }

}
