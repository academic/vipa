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
        $client = $this->client;
        $client->request('GET','/');
        $this->assertStatusCode(200,$client);
    }

}