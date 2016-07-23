<?php

namespace Ojs\SiteBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class JournalControllerTest extends BaseTestCase
{
    public function testArchiveIndex()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/intro/archive');
        $this->assertStatusCode(200,$client);
    }

    public function testJournalBoard()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/intro/board');
        $this->assertStatusCode(200,$client);
    }

    public function testJournalContacts()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/intro/contacts');
        $this->assertStatusCode(200,$client);
    }

    public function testJournalIndex()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/intro');
        $this->assertStatusCode(200, $client);
    }

    public function testLastArticles()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/intro/last');
        $this->assertStatusCode(200,$client);
    }
}
