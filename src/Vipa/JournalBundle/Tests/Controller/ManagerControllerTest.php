<?php

namespace Vipa\JournalBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class ManagerControllerTest extends BaseTestCase
{
    public function testJournalSettings()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/settings/');

        $this->assertStatusCode(200, $client);
    }

    public function testJournalSettingsSubmission()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/settings/submission');

        $this->assertStatusCode(200, $client);
    }

    public function testUserIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/user');

        $this->assertStatusCode(200, $client);
    }
}