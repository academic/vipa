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

    public function testJournalArticles()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/intro/articles');
        $this->assertStatusCode(200, $client);
    }

    public function TestIssuePage()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/intro/issue/1');
        $this->assertStatusCode(200,$client);
    }

}