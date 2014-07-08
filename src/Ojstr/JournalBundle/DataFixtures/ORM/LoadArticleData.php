<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Article;

class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $journal = $this->getReference('ref-journal');
        if (!isset($journal)) {
            return;
        }
        $ujr = $this->getReference('ref-ujr-author');
        
        $article = new Article();
        $article->setAbstract("Article abstract article abstract article abstract article abstract ");
        $article->setDoi("123321321");
        $article->setFirstPage(1);
        $article->setIsAnonymous(FALSE);
        $article->setJournal($journal);
        $article->setKeywords("key1, key2, key3");
        $article->setLastPage(10);
        $article->setPubdate(new \DateTime());
        $article->setStatus(0);
        $article->setSubjects("subject1, subject2");
        $article->setTitle("Article Title");
        $article->setSubmitterId($ujr->getUserId());
        $manager->persist($article);
        $manager->flush();
    }

    public function getOrder() {
        return 17;
    }

}
