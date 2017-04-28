<?php

namespace Vipa\AdminBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class AdminPublisherApplicationControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/application/publisher');

        $this->assertStatusCode(200, $client);
    }

    public function testDetail()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/application/publisher/2');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/application/publisher/2/edit');

        $this->assertStatusCode(200, $client);
    }

    public function testSave()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/application/publisher/2/save');

        $this->assertStatusCode(302, $client);
    }

    public function testReject()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/application/publisher/2/reject');

        $this->assertStatusCode(302, $client);
    }
}