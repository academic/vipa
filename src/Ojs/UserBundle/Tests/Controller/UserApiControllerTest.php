<?php

namespace Ojs\UserBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class UserControllerTest extends BaseTestCase
{
    public function testApiKey()
    {
        $client = $this->client;
        $crawler = $client->request('GET', '/user/api/key');
        $this->assertStatusCode(200,$client);
    }
}
