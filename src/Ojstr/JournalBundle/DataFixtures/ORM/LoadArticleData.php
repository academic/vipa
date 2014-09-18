<?php

namespace Ojstr\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Article;

class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $journal = $this->getReference('ref-journal');
        $journal2 = $this->getReference('ref-journal2');
        $section = $this->getReference('ref-section');

        if (!isset($journal)) {
            return;
        }
        $ujr = $this->getReference('ref-ujr-author');

        $article = new Article();
        $article->setAbstract("Article abstract article abstract article abstract article abstract ");
        $article->setDoi("123321321");
        $article->setFirstPage(1);
        $article->setIsAnonymous(false);
        $article->setJournal($journal);
        $article->setKeywords("key1, key2, key3");
        $article->setLastPage(10);
        $article->setPubdate(new \DateTime());
        $article->setStatus(0);
        $article->setSubjects("subject1, subject2");
        $article->setTitle("Article Title");
        $article->setSubmitterId($ujr->getUserId());
        $article->setSection($section);
        $manager->persist($article);
        $this->addReference('ref-article', $article);

        $article2 = new Article();
        $article2->setAbstract("Article abstract lipsum");
        $article2->setDoi("98989898");
        $article2->setFirstPage(1);
        $article2->setIsAnonymous(false);
        $article2->setJournal($journal2);
        $article2->setKeywords("key1, key2, key3");
        $article2->setLastPage(10);
        $article2->setPubdate(new \DateTime());
        $article2->setStatus(0);
        $article2->setSubjects("subject1, subject2");
        $article2->setTitle("Article2 Title");
        $article2->setSubmitterId($ujr->getUserId());
        $article2->setSection($section);
        $manager->persist($article2);
        $this->addReference('ref-article2', $article2);

        $manager->flush();
    }

    public function getOrder()
    {
        return 17;
    }

}
