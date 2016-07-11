<?php

namespace Ojs\CoreBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use h4cc\AliceFixturesBundle\Fixtures\FixtureManagerInterface;
use Ojs\JournalBundle\Entity\ArticleTypes;
use Ojs\JournalBundle\Entity\ContactTypes;
use Ojs\JournalBundle\Entity\Index;

class SampleObjectLoader
{
    /**
     * @var FixtureManagerInterface
     */
    protected $aliceManager;

    /**
     * @var string
     */
    protected $fixturesRoot;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(FixtureManagerInterface $aliceManager, EntityManagerInterface $em, $locale)
    {
        $this->aliceManager = $aliceManager;
        $this->em           = $em;
        $this->locale       = $locale;
        $this->fixturesRoot = __DIR__.'/../Tests/DataFixtures/ORM/';
    }

    /**
     * @return int
     */
    public function loadAnnouncement()
    {
        $objects = $this->aliceManager->loadFiles([$this->fixturesRoot.'announcement.yml']);
        $this->aliceManager->persist($objects, false);

        return $objects['announcement']->getId();
    }

    /**
     * @return int
     */
    public function loadArticleType()
    {
        $entity = new ArticleTypes();
        $entity
            ->setCurrentLocale($this->locale)
            ->setName('Sample Article Type Name - '. $this->locale)
            ->setDescription('Sample Article Type Description - '. $this->locale)
            ;
        $this->em->persist($entity);
        $this->em->flush();

        return $entity->getId();
    }

    /**
     * @return int
     */
    public function loadContact()
    {
        $objects = $this->aliceManager->loadFiles([$this->fixturesRoot.'contact.yml']);
        $this->aliceManager->persist($objects, false);

        return $objects['contact']->getId();
    }

    /**
     * @return int
     */
    public function loadContactType()
    {
        $entity = new ContactTypes();
        $entity
            ->setCurrentLocale($this->locale)
            ->setName('Sample Contact Type Name - '. $this->locale)
            ->setDescription('Sample Contact Type Description - '. $this->locale)
        ;
        $this->em->persist($entity);
        $this->em->flush();

        return $entity->getId();
    }

    /**
     * @return int
     */
    public function loadIndex()
    {
        $entity = new Index();
        $entity
            ->setName('Sample index')
            ->setStatus(true)
        ;
        $this->em->persist($entity);
        $this->em->flush();

        return $entity->getId();
    }

    /**
     * @return int
     */
    public function loadInstitution()
    {
        $objects = $this->aliceManager->loadFiles([$this->fixturesRoot.'institution.yml']);
        $this->aliceManager->persist($objects, false);

        return $objects['institution']->getId();
    }

    /**
     * @return int
     */
    public function loadArticleAuthor()
    {
        $objects = $this->aliceManager->loadFiles([$this->fixturesRoot.'article_author.yml']);
        $this->aliceManager->persist($objects, false);

        return $objects['article_author']->getId();
    }

    /**
     * @return int
     */
    public function loadArticleCitation()
    {
        $objects = $this->aliceManager->loadFiles([$this->fixturesRoot.'article_citation.yml']);
        $this->aliceManager->persist($objects, false);

        return $objects['article_citation']->getId();
    }

    /**
     * @return int
     */
    public function loadArticleFile()
    {
        $objects = $this->aliceManager->loadFiles([$this->fixturesRoot.'article_file.yml']);
        $this->aliceManager->persist($objects, false);

        return $objects['article_file']->getId();
    }

    /**
     * @return int
     */
    public function loadArticle()
    {
        $objects = $this->aliceManager->loadFiles([$this->fixturesRoot.'article.yml']);
        $this->aliceManager->persist($objects, false);

        return $objects['article']->getId();
    }

    /**
     * @return int
     */
    public function loadBoard()
    {
        $objects = $this->aliceManager->loadFiles([$this->fixturesRoot.'board.yml']);
        $this->aliceManager->persist($objects, false);

        return $objects['board']->getId();
    }

    /**
     * @return int
     */
    public function loadIssue()
    {
        $objects = $this->aliceManager->loadFiles([$this->fixturesRoot.'issue.yml']);
        $this->aliceManager->persist($objects, false);

        return $objects['issue']->getId();
    }

    /**
     * @return int
     */
    public function loadJournal()
    {
        $objects = $this->aliceManager->loadFiles([$this->fixturesRoot.'journal.yml']);
        $this->aliceManager->persist($objects, false);

        return $objects['journal']->getId();
    }
}
