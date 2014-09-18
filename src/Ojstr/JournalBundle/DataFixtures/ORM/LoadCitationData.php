<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Citation;

class LoadCitationData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $article = $manager->createQuery('SELECT c FROM OjstrJournalBundle:Article c')
                        ->setMaxResults(1)->getResult();
        if (isset($article[0])) {
            $c = new Citation();
            $c->addArticle($article[0]);
            $c->setOrderNum(0);
            $c->setRaw("Citation raw");
            $c->setType('article');
            $manager->persist($c);
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 13;
    }

}
