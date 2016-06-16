<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class DesignControllerTest extends BaseTestCase
{

    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/design/');

        $this->assertStatusCode(200,$client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/design/new');

        $this->assertStatusCode(200,$client);
    }

}