<?php

namespace Ojs\CoreBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use h4cc\AliceFixturesBundle\Fixtures\FixtureManagerInterface;
use Ojs\JournalBundle\Entity\ArticleTypes;
use Ojs\JournalBundle\Entity\ContactTypes;

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
}
