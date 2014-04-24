<?php

namespace Ojstr\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\JournalBundle\Entity\Article;
use Ojstr\JournalBundle\Entity\Journal;

class LoadJournalData implements FixtureInterface {

    public function load(ObjectManager $manager) {
        $journal = new Journal();
        $journal->setIssn("1300-7041");
        $journal->setMission("Mission text");
        $journal->setPeriod("2");
        $journal->setPublishStatus(0);
        $journal->setScope("Scope text");
        $journal->setSubtitle("Subtitle text");
        $journal->setTitle("Example Journal");
        $journal->setTitleAbbr("EXJ");
        $journal->setTitleTranslated(NULL);
        $journal->setUrl("https://example.gov");
        $manager->persist($journal);
        $manager->flush();
        /*
          $article = new Article();
          $article->setAbstract('Article abstract text');
          $article->setAbstractTranslated('Article abstract text - translated');
          $article->setDoi('10.1000/186');
          $article->setFirstPage(10);
          $article->setLastPage(20);
          $manager->persist($article);
          $manager->flush();
         * */
    }

}
