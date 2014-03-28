<?php

namespace Ojstr\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase {

    public function testAuth() {
        $client = static::createClient();
        $client->request('GET', '/admin/user/');
        $this->assertEquals(302, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /user/");
    }

}
