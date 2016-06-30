<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class ContactTypesRestControllerTest extends ApiBaseTestCase
{
    public function testGetContacttypesAction()
    {
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_contacttypes', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetContacttypeAction()
    {
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_get_contacttype', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewContacttypeAction()
    {
        $content = [];
        $content['translations'] = [
            'en' => [
                'name' => 'PHPUnit Test Name Field - POST',
                'description' => 'PHPUnit Test Description Field - POST'
            ]
        ];
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_contacttypes', $routeParameters);
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

    public function testPutContacttypeAction()
    {
        $content = [];
        $content['translations'] = [
            'en' => [
                'name' => 'PHPUnit Test Name Field - Put',
                'description' => 'PHPUnit Test Description Field - Put'
            ],
            'tr' => [
                'name' => 'PHPUnit Test Name Field tr - Put',
                'description' => 'PHPUnit Test Description Field tr - Put'
            ],
        ];
        $routeParameters = $this->getRouteParams(['id'=> 550]);
        $url = $this->router->generate('api_1_put_contacttype', $routeParameters);
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

    public function testPatchContacttypeAction()
    {
        $content = [];
        $content['translations'] = [
            'tr' => [
                'name' => 'PHPUnit Test Name Field tr - Patch',
                'description' => 'PHPUnit Test Description Field tr - Patch'
            ],
        ];
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_patch_contacttype', $routeParameters);
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

    public function testDeleteContacttypeAction()
    {
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_delete_contacttype', $routeParameters);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
