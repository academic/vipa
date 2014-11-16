<?php

namespace Ojs\ApiBundle\Tests;

use Ojs\Common\Tests\BaseTestCase;

class RestApiTest extends BaseTestCase
{

    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(), $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'), $response->headers
        );
    }

    public function testUsersGet()
    {
        $this->client->request('GET', '/api/users.json', [
            'apikey' => 'MWFlZDFlMTUwYzRiNmI2NDU3NzNkZDA2MzEyNzJkNTE5NmJmZjkyZQ=='
        ]);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
    }


}
