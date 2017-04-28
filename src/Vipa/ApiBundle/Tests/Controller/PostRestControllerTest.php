<?php

namespace Vipa\ApiBundle\Tests\Controller;

use Vipa\ApiBundle\Tests\ApiBaseTestCase;

class PostRestControllerTest extends ApiBaseTestCase
{
    public function testNewPostAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/posts/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetPostsAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/posts?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostPostAction()
    {
        $content = [
            'translations' => [
                $this->locale => [
                    'title' => 'PHPUnit Test Title Field '.$this->locale.' - POST',
                    'content' => 'PHPUnit Test Content Field '.$this->locale.' - POST',
                ]
            ]
        ];
        $this->client->request(
            'POST',
            '/api/v1/posts?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetPostAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/posts/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutPostAction()
    {
        $content = [
            'translations' => [
                $this->locale => [
                    'title' => 'PHPUnit Test Title Field '.$this->locale.' - PUT',
                    'content' => 'PHPUnit Test Content Field '.$this->locale.' - PUT',
                ]
            ],
        ];
        $this->client->request(
            'PUT',
            '/api/v1/posts/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchPostAction()
    {
        $id = $this->sampleObjectLoader->loadPost();
        $content = [
            'translations' => [
                $this->secondLocale => [
                    'title' => 'PHPUnit Test Title Field '.$this->secondLocale.' - PATCH',
                    'content' => 'PHPUnit Test Content Field '.$this->secondLocale.' - PATCH',
                ]
            ]
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/posts/'.$id.'?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeletePostAction()
    {
        $entityId = $this->sampleObjectLoader->loadPost();
        $this->client->request(
            'DELETE',
            '/api/v1/posts/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
