<?php

namespace Vipa\AdminBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class AdminJournalApplicationControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/application/journal');

        $this->assertStatusCode(200, $client);
    }

    public function testDetail()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/application/journal/1');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/application/journal/edit/1');

        $this->assertStatusCode(200, $client);
    }
}