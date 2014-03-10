<?php

namespace Ojstr\JournalBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ThemeControllerTest extends WebTestCase {

    public function testStatus() {
        $client = static::createClient();
        $client->request('GET', '/admin/theme/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /theme/");
    }

}
