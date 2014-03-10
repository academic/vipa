<?php

namespace Ojstr\JournalBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JournalControllerTest extends WebTestCase {

    public function testStatus() {
        $client = static::createClient();
        $client->request('GET', '/admin/journal/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /journal/");
    }

}
