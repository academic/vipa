<?php

namespace Vipa\SiteBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class JournalControllerTest extends BaseTestCase
{
    public function testArchiveIndex()
    {
        $client = $this->client;
        $client->request('GET','/intro/archive');
        $this->assertStatusCode(200,$client);
    }

    public function testJournalBoard()
    {
        $client = $this->client;
        $client->request('GET','/intro/board');
        $this->assertStatusCode(200,$client);
    }

    public function testJournalContacts()
    {
        $client = $this->client;
        $client->request('GET','/intro/contacts');
        $this->assertStatusCode(200,$client);
    }

    public function testJournalIndex()
    {
        $client = $this->client;
        $session = $client->getContainer()->get('session');
        $session->set('_locale','tr');
        $session->save();
        $client->request('GET','/intro');
        $this->assertStatusCode(200, $client);

    }

    public function testLastArticles()
    {
        $client = $this->client;
        $client->request('GET','/intro/last');
        $this->assertStatusCode(200,$client);
    }
}
