<?php

namespace Ojs\SiteBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class SiteControllerTest extends BaseTestCase
{

    public function testIndex()
    {
        $this->client->request('GET','/');
        $this->assertStatusCode(200,$this->client);
    }

    public function testPublisherPage()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/');
        $this->assertStatusCode(200,$client);
    }

    public function testJournalIndex()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/intro');
        $this->assertStatusCode(200,$client);
    }

    public function testJournalArticles()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/intro/articles');
        $this->assertStatusCode(200,$client);
    }

    public function testJournalBoard()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/intro/board');
        $this->assertStatusCode(200,$client);
    }

    public function testLastArticles()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/intro/last');
        $this->assertStatusCode(200,$client);
    }

    public function testArchiveIndex()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/intro/archive');
        $this->assertStatusCode(200,$client);
    }

    public function testAnnouncementIndex()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/intro/announcements');
        $this->assertStatusCode(200,$client);
    }

    /**
    public function testSubscribe()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/intro/subscribe');
        $this->assertStatusCode(200,$client);
    }
     */

    public function TestIssuePage()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/intro/issue/1');
        $this->assertStatusCode(200,$client);
    }

    public function TestJournalContacts()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/intro/contacts');
        $this->assertStatusCode(200,$client);
    }

}