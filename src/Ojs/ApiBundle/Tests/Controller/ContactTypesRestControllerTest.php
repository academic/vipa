<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class ContactTypesRestControllerTest extends BaseTestCase
{
    public function testGetContacttypesAction()
    {
        $url = $this->router->generate('api_1_get_contacttypes');
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetContacttypeAction()
    {
        $url = $this->router->generate('api_1_get_contacttype', ['id'=> 1]);
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
        $url = $this->router->generate('api_1_get_contacttypes');
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
        $url = $this->router->generate('api_1_put_contacttype', ['id' => 550]);
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
        $url = $this->router->generate('api_1_patch_contacttype', ['id' => 1]);
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
        $url = $this->router->generate('api_1_delete_contacttype', ['id' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
