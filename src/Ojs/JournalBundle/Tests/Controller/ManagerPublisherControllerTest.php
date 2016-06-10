<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class ManagerPublisherControllerTest extends BaseTestCase
{
    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/publisher/1/edit');

        $this->assertStatusCode(200, $client);
    }
}