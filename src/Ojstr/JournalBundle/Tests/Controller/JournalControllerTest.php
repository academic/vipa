<?php

namespace Ojstr\JournalBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JournalControllerTest extends WebTestCase
{

    public function testStatus()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/journal/');
        $this->assertTrue(in_array($client->getResponse()->getStatusCode(), array(302, 200)),
            "Unexpected HTTP status code for GET /admin/journal/");
    }

}
