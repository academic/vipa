<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class IndexRestControllerTest extends ApiBaseTestCase
{
    public function testNewIndexesAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/indexes/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetIndexesAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/indexes?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostIndexesAction()
    {
        $content = [
            'name' => 'PHPUnitTest Name Field - POST',
            'logo' => 'http://logo.com/POST',
            'status' => true,
        ];
        $this->client->request(
            'POST',
            '/api/v1/indexes?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetIndexsAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/indexs/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutIndexesAction()
    {
        $content = [
            'name' => 'PHPUnitTest Name Field - PUT',
            'logo' => 'http://logo.com/PUT',
            'status' => true,
        ];
        $this->client->request(
            'PUT',
            '/api/v1/indexes/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchIndexesAction()
    {
        $content = [
            'name' => 'PHPUnitTest Name Field - PATCH',
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/indexes/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeleteIndexesAction()
    {
        $announcementId = $this->sampleObjectLoader->loadIndex();
        $this->client->request(
            'DELETE',
            '/api/v1/indexes/'.$announcementId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
