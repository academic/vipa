<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class PublisherThemeRestControllerTest extends ApiBaseTestCase
{
    public function testNewPublisherThemeAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/publisherthemes/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetPublisherThemesAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/publisherthemes?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostPublisherThemeAction()
    {
        $content = [
            'publisher' => 1,
            'title' => 'PHPUnit Test Title Field en - POST',
            'public' => 1,
            'css' => '*{color: red;}'
        ];
        $this->client->request(
            'POST',
            '/api/v1/publisherthemes?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetPublisherThemeAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/publisherthemes/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutPublisherThemeAction()
    {
        $content = [
            'publisher' => 1,
            'title' => 'PHPUnit Test Title Field en - PUT',
            'public' => 1,
            'css' => '*{color: red;}'
        ];
        $this->client->request(
            'PUT',
            '/api/v1/publisherthemes/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchPublisherThemeAction()
    {
        $content = [
            'title' => 'PHPUnit Test Title Field en - PATCH',
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/publisherthemes/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeletePublisherThemeAction()
    {
        $entityId = $this->sampleObjectLoader->loadPublisherTheme();
        $this->client->request(
            'DELETE',
            '/api/v1/publisherthemes/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
