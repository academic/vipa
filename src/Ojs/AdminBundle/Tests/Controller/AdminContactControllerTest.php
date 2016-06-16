<?php

namespace Ojs\AdminBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class AdminContactControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/contact/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/contact/new');

        $this->assertStatusCode(200, $client);
    }
}