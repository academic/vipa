<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\CitationSetting;

class LoadCitationSettingData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $citation = $manager->createQuery('SELECT c FROM OjstrJournalBundle:Citation c')
                        ->setMaxResults(1)->getResult();
        if (isset($citation[0])) {
            $cs = new CitationSetting();
            $cs->setCitation($citation[0]);
            $cs->setSetting("ISSN");
            $cs->setValue("321-321321");
            $manager->persist($cs);
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 14;
    }

}
