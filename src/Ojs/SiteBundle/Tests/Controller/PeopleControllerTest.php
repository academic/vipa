<?php

namespace Ojs\SiteBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class PeopleControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $client = $this->client;
        $crawler = $client->request('GET', '/explore/people');
        $this->assertStatusCode(200, $client);
    }

}
