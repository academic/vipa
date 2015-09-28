<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class PublisherTypeRestControllerTest extends BaseTestCase
{
    public function testGetPublisherTypesAction()
    {
        $url = $this->router->generate('api_1_get_publishertypes');
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
        $url = $this->router->generate('api_1_get_publishertypes');
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
        $url = $this->router->generate('api_1_get_publishertype', ['id'=> 1]);
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
        $url = $this->router->generate('api_1_put_publishertype', ['id' => 550]);
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
        $url = $this->router->generate('api_1_patch_publishertype', ['id' => 1]);
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
        $url = $this->router->generate('api_1_delete_publishertype', ['id' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
