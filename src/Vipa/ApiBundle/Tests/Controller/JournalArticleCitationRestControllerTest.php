<?php

namespace Vipa\ApiBundle\Tests\Controller;

use Vipa\ApiBundle\Tests\ApiBaseTestCase;

class JournalArticleCitationRestControllerTest extends ApiBaseTestCase
{
    public function testNewAuthorsAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/article/1/citations/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetAuthorsAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/article/1/citations?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }


    public function testPostAuthorAction()
    {
        $content = [
            'raw' => 'Hello raw citation POST',
            'type' => 1,
            'orderNum' => 3,
        ];
        $this->client->request(
            'POST',
            '/api/v1/journal/1/article/1/citations?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetAuthorAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/article/1/citations/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutAuthorAction()
    {
        $content = [
            'raw' => 'Hello raw citation PUT',
            'type' => 2,
            'orderNum' => 2,
        ];
        $this->client->request(
            'PUT',
            '/api/v1/journal/1/article/1/citations/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchAuthorAction()
    {
        $content = [
            'raw' => 'Hello raw citation PATCH',
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/journal/1/article/1/citations/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeleteAuthorAction()
    {
        $entityId = $this->sampleObjectLoader->loadArticleCitation();
        $this->client->request(
            'DELETE',
            '/api/v1/journal/1/article/1/citations/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
