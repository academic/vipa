<?php

namespace Vipa\ApiBundle\Tests\Controller;

use Vipa\ApiBundle\Tests\ApiBaseTestCase;

class DefaultControllerTest extends ApiBaseTestCase
{
    public function testIndex()
    {
        $client = $this->client;
        $client->request('GET', '/api/public/v1/index');

        $this->assertStatusCode(200, $client);
    }
}
