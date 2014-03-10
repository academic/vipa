<?php

namespace Ojstr\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase {

    public function testAuth() {
        $client = static::createClient();
        $client->request('GET', '/user/');
        $this->assertEquals(302, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /user/");
    }

}
