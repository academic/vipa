<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class IssueControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/issue');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/issue/new');

        $this->assertStatusCode(200, $client);
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/issue/1');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/issue/1/edit');

        $this->assertStatusCode(200, $client);
    }

    public function testArrange()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/issue/1/arrange');

        $this->assertStatusCode(200, $client);
    }

    public function testView()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/issue/1/article');

        $this->assertStatusCode(200, $client);
    }

    public function testMakeLastIssue()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/issue/1/make-last');

        $this->assertStatusCode(302, $client);
    }
}