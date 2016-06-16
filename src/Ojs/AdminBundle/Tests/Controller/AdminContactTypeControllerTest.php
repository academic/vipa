<?php

namespace Ojs\AdminBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class AdminContactTypeControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/contact-type/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/contact-type/new');

        $this->assertStatusCode(200, $client);
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/contact-type/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/contact-type/1/edit');

        $this->assertStatusCode(200, $client);
    }
}