<?php

namespace Vipa\SiteBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class IssueControllerTest extends BaseTestCase
{
    public function testIssuePage()
    {
        $client = $this->client;
        $client->request('GET', '/intro/issue/1');
        $this->assertStatusCode(200, $client);
    }

}

