<?php

namespace Vipa\CoreBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use h4cc\AliceFixturesBundle\Fixtures\FixtureManagerInterface;
use Vipa\AdminBundle\Entity\AdminPage;
use Vipa\AdminBundle\Entity\AdminPost;
use Vipa\AdminBundle\Entity\PublisherManagers;
use Vipa\JournalBundle\Entity\ArticleTypes;
use Vipa\JournalBundle\Entity\ContactTypes;
use Vipa\JournalBundle\Entity\Index;
use Vipa\JournalBundle\Entity\Journal;
use Vipa\JournalBundle\Entity\JournalIndex;
use Vipa\JournalBundle\Entity\JournalPage;
use Vipa\JournalBundle\Entity\JournalPost;
use Vipa\JournalBundle\Entity\JournalTheme;
use Vipa\JournalBundle\Entity\Lang;
use Vipa\JournalBundle\Entity\MailTemplate;
use Vipa\JournalBundle\Entity\Period;
use Vipa\JournalBundle\Entity\PersonTitle;
use Vipa\JournalBundle\Entity\Publisher;
use Vipa\JournalBundle\Entity\PublisherTheme;
use Vipa\JournalBundle\Entity\PublisherTypes;
use Vipa\JournalBundle\Entity\Subject;
use Vipa\UserBundle\Entity\User;

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
    public function loadJournalIndex()
    {

        $journal = $this->em->getRepository(Journal::class)->find(1);
        $index = $this->em->getRepository(Index::class)->find(1);

        $entity = new JournalIndex();
        $entity->setIndex($index);
        $entity->setLink('http://vipa.io');
        $entity->setJournal($journal);

        $this->em->persist($entity);
        $this->em->flush();

        return $entity->getId();
    }

    /**
     * @return int
     */
    public function loadJournalSubmissionFile()
    {
        $objects = $this->aliceManager->loadFiles([$this->fixturesRoot . 'submission_file.yml']);
        $this->aliceManager->persist($objects, false);

        return $objects['submission_file']->getId();
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
    public function loadIssueFile()
    {
        $objects = $this->aliceManager->loadFiles([$this->fixturesRoot.'issue_file.yml']);
        $this->aliceManager->persist($objects, false);

        return $objects['issue_file']->getId();
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

    /**
     * @return int
     */
    public function loadSection()
    {
        $objects = $this->aliceManager->loadFiles([$this->fixturesRoot.'section.yml']);
        $this->aliceManager->persist($objects, false);

        return $objects['section']->getId();
    }

    /**
     * @return int
     */
    public function loadJournalTheme()
    {
        $journal = $this->em->getRepository(Journal::class)->find(1);
        $entity = new JournalTheme();
        $entity
            ->setTitle('Sample Journal Theme')
            ->setJournal($journal)
            ->setPublic(true)
            ;
        $this->em->persist($entity);
        $this->em->flush();

        return $entity->getId();
    }

    /**
     * @return int
     */
    public function loadLang()
    {
        $entity = new Lang();
        $entity
            ->setCode('be')
            ->setName('Sample Behrami Lang')
        ;
        $this->em->persist($entity);
        $this->em->flush();

        return $entity->getId();
    }

    /**
     * @return int
     */
    public function loadPage()
    {
        $entity = new AdminPage();
        $entity
            ->setCurrentLocale('tr')
            ->setTitle('Sample Page Title')
            ->setBody('Sample Page Body')
            ->setSlug('Sample Page Slug')
            ->setCurrentLocale('en')
            ->setTitle('Sample Page Title en')
            ->setBody('Sample Page Body en')
        ;
        $this->em->persist($entity);
        $this->em->flush();

        return $entity->getId();
    }

    /**
     * @return int
     */
    public function loadJournalPage()
    {

        $journal = $this->em->getRepository(Journal::class)->find(1);
        $entity = new JournalPage();
        $entity
            ->setCurrentLocale('tr')
            ->setTitle('Title')
            ->setSlug('title-page')
            ->setBody('Content')
            ->setVisible(true)
            ->setTags('tag')
            ->setJournal($journal);

        $this->em->persist($entity);
        $this->em->flush();

        return $entity->getId();
    }

    /**
     * @return int
     */
    public function loadJournalPost()
    {

        $journal = $this->em->getRepository(Journal::class)->find(1);
        $entity = new JournalPost();
        $entity
            ->setCurrentLocale('tr')
            ->setTitle('Post Title')
            ->setSlug('post-title')
            ->setContent('Content')
            ->setJournal($journal);

        $this->em->persist($entity);
        $this->em->flush();

        return $entity->getId();
    }

    /**
     * @return int
     */
    public function loadPeriod()
    {
        $entity = new Period();
        $entity
            ->setCurrentLocale($this->locale)
            ->setPeriod('Sample Period')
        ;
        $this->em->persist($entity);
        $this->em->flush();

        return $entity->getId();
    }

    /**
     * @return int
     */
    public function loadPersonTitle()
    {
        $entity = new PersonTitle();
        $entity
            ->setCurrentLocale($this->locale)
            ->setTitle('Sample Person Title')
        ;
        $this->em->persist($entity);
        $this->em->flush();

        return $entity->getId();
    }

    /**
     * @return int
     */
    public function loadPost()
    {
        $entity = new AdminPost();
        $entity
            ->setCurrentLocale('tr')
            ->setTitle('Sample Post Title')
            ->setContent('Sample Post Content')
            ->setCurrentLocale('en')
            ->setTitle('Sample Post Title en')
            ->setContent('Sample Post Content en')
        ;
        $this->em->persist($entity);
        $this->em->flush();

        return $entity->getId();
    }

    /**
     * @return int
     */
    public function loadPublisherManager()
    {
        $entity = new PublisherManagers();
        $entity
            ->setPublisher($this->em->getRepository(Publisher::class)->find(1))
            ->setUser($this->em->getRepository(User::class)->find(6))
        ;
        $this->em->persist($entity);
        $this->em->flush();

        return $entity->getId();
    }

    /**
     * @return int
     */
    public function loadPublisher()
    {
        $objects = $this->aliceManager->loadFiles([$this->fixturesRoot.'publisher.yml']);
        $this->aliceManager->persist($objects, false);

        return $objects['publisher']->getId();
    }

    /**
     * @return int
     */
    public function loadPublisherTheme()
    {
        $entity = new PublisherTheme();
        $entity
            ->setPublisher($this->em->getRepository(Publisher::class)->find(1))
            ->setTitle('Sample Publisher Theme')
            ->setPublic(true)
            ->setCss('.sample-css{}')
        ;
        $this->em->persist($entity);
        $this->em->flush();

        return $entity->getId();
    }

    /**
     * @return int
     */
    public function loadPublisherType()
    {
        $entity = new PublisherTypes();
        $entity
            ->setCurrentLocale($this->locale)
            ->setName('Sample Publisher Type Name')
            ->setDescription('Sample Publisher type Description')
        ;
        $this->em->persist($entity);
        $this->em->flush();

        return $entity->getId();
    }

    /**
     * @return int
     */
    public function loadSubject()
    {
        $entity = new Subject();
        $entity
            ->setCurrentLocale($this->locale)
            ->setSubject('Sample Subject Name')
            ->setDescription('Sample Subject Description')
        ;
        $this->em->persist($entity);
        $this->em->flush();

        return $entity->getId();
    }

    /**
     * @return int
     */
    public function loadUser()
    {
        $objects = $this->aliceManager->loadFiles([$this->fixturesRoot.'user.yml']);
        $this->aliceManager->persist($objects, false);

        return $objects['user']->getId();
    }

    /**
     * @return int
     */
    public function fetchMailTemplate()
    {
        $journal = $this->em->getRepository(Journal::class)->find(1);
        return $this->em->getRepository(MailTemplate::class)->findOneBy([
            'journal' => $journal,
        ]);
    }


}
