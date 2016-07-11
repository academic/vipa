<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class PublisherManagerRestControllerTest extends ApiBaseTestCase
{
    public function testNewPublisherManagerAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/publishermanagers/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetPublisherManagersAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/publishermanagers?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostPublisherManagerAction()
    {
        $content = [
            'publisher' => 1,
            'user' => rand(1,100),
        ];
        $this->client->request(
            'POST',
            '/api/v1/publishermanagers?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetPublisherManagerAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/publishermanagers/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutPublisherManagerAction()
    {
        $content = [
            'publisher' => 1,
            'user' => rand(1,100),
        ];
        $this->client->request(
            'PUT',
            '/api/v1/publishermanagers/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchPublisherManagerAction()
    {
        $content = [
            'user' => rand(1,100)
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/publishermanagers/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeletePublisherManagerAction()
    {
        $entityId = $this->sampleObjectLoader->loadPublisherManager();
        $this->client->request(
            'DELETE',
            '/api/v1/publishermanagers/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
