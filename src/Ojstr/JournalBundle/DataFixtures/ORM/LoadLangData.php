<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Lang;

class LoadLangData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $data = array(
            array("code" => "tr", "name" => "Turkish"),
            array("code" => "en", "name" => "English"),
            array("code" => "fr", "name" => "French"),
            array("code" => "it", "name" => "Italian"),
            array("code" => "de", "name" => "German"),
            array("code" => "de", "name" => "German"),
            array("code" => "ar", "name" => "Arabic", "rtl" => TRUE),
            array("code" => "es", "name" => "Spanish"),
            array("code" => "ru", "name" => "Russian")
        );
        foreach ($data as $item) {
            $lang = new Lang();
            $lang->setCode($item['code']);
            $lang->setName($item['name']);
            $lang->setRtl(isset($item['rtl']) ? $item['rtl'] : false);
            $manager->persist($lang);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 0;
    }

}
