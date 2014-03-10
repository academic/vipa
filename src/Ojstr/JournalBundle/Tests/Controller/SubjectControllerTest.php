<?php

namespace Ojstr\JournalBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SubjectControllerTest extends WebTestCase {

    public function testCompleteScenario() {
        $client = static::createClient();
        $client->request('GET', '/admin/subject/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /admin/subject/");
    }

}
