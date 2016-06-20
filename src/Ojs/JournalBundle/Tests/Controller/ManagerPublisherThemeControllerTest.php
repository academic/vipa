<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class ManagerPublisherThemeControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/publisher/1/theme/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/publisher/1/theme/new');

        $this->assertStatusCode(200, $client);
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/publisher/1/theme/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/publisher/1/theme/1/edit');

        $this->assertStatusCode(200, $client);
    }
}