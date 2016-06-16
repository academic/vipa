<?php

namespace Ojs\UserBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class SecurityControllerTest extends BaseTestCase
{
    public function testLogin()
    {
        $client = $this->client;
        $crawler = $client->request('GET', '/login');
        $this->assertStatusCode(200, $client);
    }

    public function testPasswordCreate()
    {
        $client = $this->client;
        $crawler = $client->request('GET', '/password/create');
        $this->assertStatusCode(302, $client);
    }
}
