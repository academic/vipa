<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class LangRestControllerTest extends ApiBaseTestCase
{
    public function testNewLangAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/langs/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetLangsAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/langs?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostLangAction()
    {
        $content = [
            'code' => 'ph',
            'name' => 'PHPUnit Name Field - POST',
            'rtl' => false,
        ];
        $this->client->request(
            'POST',
            '/api/v1/langs?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetLangAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/langs/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutLangAction()
    {
        $content = [
            'code' => 'bc',
            'name' => 'PHPUnit Name Field - PUT',
            'rtl' => true,
        ];
        $this->client->request(
            'PUT',
            '/api/v1/langs/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchLangAction()
    {
        $content = [
            'code' => 'en',
            'name' => 'PHPUnit Name Field - PATCH',
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/langs/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeleteLangAction()
    {
        $entityId = $this->sampleObjectLoader->loadLang();
        $this->client->request(
            'DELETE',
            '/api/v1/langs/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
