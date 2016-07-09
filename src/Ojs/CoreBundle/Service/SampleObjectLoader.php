<?php

namespace Ojs\CoreBundle\Service;

use h4cc\AliceFixturesBundle\Fixtures\FixtureManagerInterface;

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

    public function __construct(FixtureManagerInterface $aliceManager)
    {
        $this->aliceManager = $aliceManager;
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
}
