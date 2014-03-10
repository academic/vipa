<?php

namespace Ojstr\JournalBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase {

    public function testCompleteScenario() {
        $client = static::createClient();
        $client->request('GET', '/admin/contact/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /contact/");
    }

}
