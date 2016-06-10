<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class JournalThemeControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/theme/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/theme/new');

        $this->assertStatusCode(200, $client);
    }

    public function testGlobalThemes()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/theme/global-themes');

        $this->assertStatusCode(200, $client);
    }
}