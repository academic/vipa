<?php

namespace Vipa\AdminBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class AdminControllerTest extends BaseTestCase
{
    public function testDashboardCheck()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/dashboard');

        $this->assertStatusCode(302, $client);
    }

    public function testDashboard()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/dashboard');

        $this->assertStatusCode(200, $client);
    }

    public function testStats()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/stats');

        $this->assertStatusCode(200, $client);
    }

    public function testIssue()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/stats/issue');

        $this->assertStatusCode(200, $client);
    }
}