<?php

namespace Ojs\JournalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use \Ojs\JournalBundle\Entity\ArticleAuthor;

class LoadArticleAuthorData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $article = $this->getReference('ref-article');
        $author1 = $this->getReference('ref-author-record1');
        $author2 = $this->getReference('ref-author-record2');

        $articleAuthor1 = new ArticleAuthor();
        $articleAuthor1->setArticle($article);
        $articleAuthor1->setAuthor($author1);
        $articleAuthor1->setAuthorOrder(0);
        $manager->persist($articleAuthor1);

        $articleAuthor2 = new ArticleAuthor();
        $articleAuthor2->setArticle($article);
        $articleAuthor2->setAuthor($author2);
        $articleAuthor2->setAuthorOrder(0);
        $manager->persist($articleAuthor2);

        $manager->flush();
    }

    public function getOrder()
    {
        return 18;
    }

}
