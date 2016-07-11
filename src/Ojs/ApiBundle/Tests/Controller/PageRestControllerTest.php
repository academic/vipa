<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class PageRestControllerTest extends ApiBaseTestCase
{
    public function testNewPageAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/pages/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetPagesAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/pages?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostPageAction()
    {
        $content = [
            'translations' => [
                $this->locale => [
                    'title' => 'PHPUnit Test Title Field '.$this->locale.' - POST',
                    'body' => 'PHPUnit Test Body Field '.$this->locale.' - POST',
                ]
            ],
            'visible' => 1,
        ];
        $this->client->request(
            'POST',
            '/api/v1/pages?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetPageAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/pages/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutPageAction()
    {
        $content = [
            'translations' => [
                $this->locale => [
                    'title' => 'PHPUnit Test Title Field '.$this->locale.' - PUT',
                    'body' => 'PHPUnit Test Body Field '.$this->locale.' - PUT',
                ]
            ],
            'visible' => 1,
        ];
        $this->client->request(
            'PUT',
            '/api/v1/pages/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchPageAction()
    {
        $content = [
            'translations' => [
                $this->secondLocale => [
                    'title' => 'PHPUnit Test Title Field '.$this->secondLocale.' - PATCH',
                    'body' => 'PHPUnit Test Body Field '.$this->secondLocale.' - PATCH',
                ]
            ]
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/pages/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeletePageAction()
    {
        $entityId = $this->sampleObjectLoader->loadPage();
        $this->client->request(
            'DELETE',
            '/api/v1/pages/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
