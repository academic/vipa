<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class PublisherThemeRestControllerTest extends ApiBaseTestCase
{
    public function testGetPublisherThemesAction()
    {
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_publisherthemes', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewPublisherThemeAction()
    {
        $content = [
            'publisher' => 1,
            'title' => 'PHPUnit Test Title Field en - POST',
            'public' => 1,
            'css' => '*{color: red;}'
        ];
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_publisherthemes', $routeParameters);
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

    public function testGetPublisherThemeAction()
    {
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_get_publishertheme', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutPublisherThemeAction()
    {
        $content = [
            'publisher' => 1,
            'title' => 'PHPUnit Test Title Field en - PUT',
            'public' => 1,
            'css' => '*{color: red;}'
        ];
        $routeParameters = $this->getRouteParams(['id'=> 550]);
        $url = $this->router->generate('api_1_put_publishertheme', $routeParameters);
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

    public function testPatchPublisherThemeAction()
    {
        $content = [
            'title' => 'PHPUnit Test Title Field en - PATCH',
        ];
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_patch_publishertheme', $routeParameters);
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

    public function testDeletePublisherThemeAction()
    {
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_delete_publishertheme', $routeParameters);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
