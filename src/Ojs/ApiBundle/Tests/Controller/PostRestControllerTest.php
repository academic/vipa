<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class PostRestControllerTest extends ApiBaseTestCase
{
    public function testGetPostsAction()
    {
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_posts', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewPostAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field en - POST',
                    'content' => 'PHPUnit Test Content Field en - POST',
                ]
            ]
        ];
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_posts', $routeParameters);
        $this->client->request(
            'POST',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $response = $this->client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testGetPostAction()
    {
        $url = $this->router->generate('api_1_get_post', ['id'=> 1]);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutPostAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field en - PUT',
                    'content' => 'PHPUnit Test Content Field en - PUT',
                ]
            ],
        ];
        $routeParameters = $this->getRouteParams(['id'=> 550]);
        $url = $this->router->generate('api_1_put_post', $routeParameters);
        $this->client->request(
            'PUT',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $response = $this->client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testPatchPostAction()
    {
        $content = [
            'translations' => [
                'tr' => [
                    'title' => 'PHPUnit Test Title Field TR - PATCH',
                    'content' => 'PHPUnit Test Content Field TR - PATCH',
                ]
            ]
        ];
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_patch_post', $routeParameters);
        $this->client->request(
            'PATCH',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testDeletePostAction()
    {
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_delete_post', $routeParameters);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
