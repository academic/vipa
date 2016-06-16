<?php

namespace Ojs\UserBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class UserApiControllerTest extends BaseTestCase
{
    public function testApiKey()
    {
        $client = $this->client;
        $crawler = $client->request('GET', '/user/apikey', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ));
        $this->assertStatusCode(200,$client);
    }

    public function testregenerateAPI()
    {
        $client = $this->client;
        $crawler = $client->request('GET', '/user/apikey/regenerate', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ));
        $this->assertStatusCode(302, $client);
    }
}
