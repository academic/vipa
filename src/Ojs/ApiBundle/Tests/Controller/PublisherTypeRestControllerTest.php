<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class PublisherTypeRestControllerTest extends ApiBaseTestCase
{
    public function testNewPublisherTypeAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/publishertypes/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetPublisherTypesAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/publishertypes?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostPublisherTypeAction()
    {
        $content = [
            'translations' => [
                $this->locale => [
                    'name' => 'PHPUnit Test Name Field '.$this->locale.' - POST',
                    'description' => 'PHPUnit Test Description Field '.$this->locale.' - POST',
                ]
            ],
        ];
        $this->client->request(
            'POST',
            '/api/v1/publishertypes?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetPublisherTypeAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/publishertypes/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutPublisherTypeAction()
    {
        $content = [
            'translations' => [
                $this->locale => [
                    'name' => 'PHPUnit Test Name Field '.$this->locale.' - PUT',
                    'description' => 'PHPUnit Test Description Field '.$this->locale.' - PUT',
                ]
            ],
        ];
        $this->client->request(
            'PUT',
            '/api/v1/publishertypes/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchPublisherTypeAction()
    {
        $content = [
            'translations' => [
                $this->secondLocale => [
                    'name' => 'PHPUnit Test Name Field '.$this->secondLocale.' - PATCH',
                ]
            ]
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/publishertypes/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeletePublisherTypeAction()
    {
        $entityId = $this->sampleObjectLoader->loadPublisherType();
        $this->client->request(
            'DELETE',
            '/api/v1/publishertypes/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
