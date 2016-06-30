<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class PageRestControllerTest extends ApiBaseTestCase
{
    public function testGetPagesAction()
    {
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_pages', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewPageAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field en - POST',
                    'body' => 'PHPUnit Test Body Field en - POST',
                ]
            ],
            'tags' => ['phpunit', 'post']
        ];
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_pages', $routeParameters);
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

    public function testGetPageAction()
    {
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_get_page', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutPageAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field en - PUT',
                    'body' => 'PHPUnit Test Body Field en - PUT',
                ]
            ],
            'tags' => ['phpunit', 'put']
        ];
        $routeParameters = $this->getRouteParams(['id'=> 550]);
        $url = $this->router->generate('api_1_put_page', $routeParameters);
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

    public function testPatchPageAction()
    {
        $content = [
            'translations' => [
                'tr' => [
                    'title' => 'PHPUnit Test Title Field TR - PATCH',
                    'body' => 'PHPUnit Test Body Field TR - PATCH',
                ]
            ]
        ];
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_patch_page', $routeParameters);
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

    public function testDeletePageAction()
    {
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_delete_page', $routeParameters);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
