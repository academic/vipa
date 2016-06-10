<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class JournalUserControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/user');

        $this->assertStatusCode(200, $client);
    }

    public function testNewUser()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/user/new');

        $this->assertStatusCode(200, $client);
    }

    public function testCreateUser()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/user/create');

        $this->assertStatusCode(200, $client);
    }

    public function testRegisterAsAuthor()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/join');

        $this->assertStatusCode(200, $client);
    }

    public function testJournals()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/my');

        $this->assertStatusCode(200, $client);
    }
}