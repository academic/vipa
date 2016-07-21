<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class ArticleTypeRestControllerTest extends ApiBaseTestCase
{
    public function testNewArticleTypeAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/articletypes/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetArticleTypesAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/articletypes?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostArticleTypeAction()
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
            '/api/v1/articletypes?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetArticleTypeAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/articletypes/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutArticleTypeAction()
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
            '/api/v1/articletypes/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchArticleTypeAction()
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
            '/api/v1/articletypes/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeleteArticleTypeAction()
    {
        $announcementId = $this->sampleObjectLoader->loadArticleType();
        $this->client->request(
            'DELETE',
            '/api/v1/articletypes/'.$announcementId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
