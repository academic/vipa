<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Theme;

class LoadThemeData implements FixtureInterface {

    public function load(ObjectManager $manager) {
        $theme = new Theme();
        $theme->setContent("<!-- theme content -->");
        $theme->setName("Demo Theme");
        $manager->persist($theme);
        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }

}
