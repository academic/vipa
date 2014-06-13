<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Article;

class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $journal = $manager->createQuery('SELECT c FROM OjstrJournalBundle:Journal c')
                        ->setMaxResults(1)->getResult();
        $subject = $manager->createQuery('SELECT c FROM OjstrJournalBundle:Subject c')
                        ->setMaxResults(1)->getResult();
        $article = new Article();
        $article->setAbstract("Article abstract article abstract article abstract article abstract ");
        $article->setDoi("123321321");
        $article->setFirstPage(1);
        $article->setIsAnonymous(FALSE);
        $article->setJournal($journal[0]);
        $article->setKeywords("key1, key2, key3");
        $article->setLastPage(10);
        $article->setPubdate(new \DateTime());
        $article->setStatus(0);
        $article->addSubject($subject[0]);
        $article->setTitle("Article Title");
        $manager->persist($article);
        $manager->flush();
    }

    public function getOrder() {
        return 11;
    }

}
