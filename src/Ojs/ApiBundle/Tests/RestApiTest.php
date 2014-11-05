<?php

namespace Ojs\ApiBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RestApiTest extends WebTestCase
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
        $client = static::createClient();
        $client->request('GET', '/api/');
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
    }

}
