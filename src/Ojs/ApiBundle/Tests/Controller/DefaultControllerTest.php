<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class DefaultControllerTest extends ApiBaseTestCase
{
    public function testIndex()
    {
        $client = $this->client;
        $client->request('GET', '/api/public/v1/index');

        $this->assertStatusCode(200, $client);
    }
}
