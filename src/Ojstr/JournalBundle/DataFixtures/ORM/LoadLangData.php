<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Lang;

class LoadLangData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {

        $data = array(
            array("code" => "tr", "name" => "Turkish", "rtl" => false),
            array("code" => "en", "name" => "English", "rtl" => false),
            array("code" => "fr", "name" => "French", "rtl" => false),
            array("code" => "it", "name" => "Italian", "rtl" => false)
        );
        foreach ($data as $item) {
            $lang = new Lang();
            $lang->setCode($item['code']);
            $lang->setName($item['name']);
            $lang->setRtl($item['rtl']);
            $manager->persist($lang);
        }
        $manager->flush();
    }

    public function getOrder() {
        return 0;
    }

}
