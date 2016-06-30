<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class PublisherTypeRestControllerTest extends ApiBaseTestCase
{
    public function testGetPublisherTypesAction()
    {
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_publishertypes', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewPublisherTypeAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'name' => 'PHPUnit Test Name Field en - POST',
                    'description' => 'PHPUnit Test Description Field en - POST',
                ]
            ],
        ];
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_publishertypes', $routeParameters);
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

    public function testGetPublisherTypeAction()
    {
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_get_publishertype', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutPublisherTypeAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'name' => 'PHPUnit Test Name Field en - PUT',
                    'description' => 'PHPUnit Test Description Field en - PUT',
                ]
            ],
        ];
        $routeParameters = $this->getRouteParams(['id'=> 550]);
        $url = $this->router->generate('api_1_put_publishertype', $routeParameters);
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

    public function testPatchPublisherTypeAction()
    {
        $content = [
            'translations' => [
                'tr' => [
                    'name' => 'PHPUnit Test Name Field TR - PATCH',
                ]
            ]
        ];
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_patch_publishertype', $routeParameters);
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

    public function testDeletePublisherTypeAction()
    {
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_delete_publishertype', $routeParameters);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
