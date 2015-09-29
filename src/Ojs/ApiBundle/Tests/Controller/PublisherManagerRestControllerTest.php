<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class PublisherManagerRestControllerTest extends BaseTestCase
{
    public function testGetPublisherManagersAction()
    {
        $url = $this->router->generate('api_1_get_publishermanagers');
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewPublisherManagerAction()
    {
        $content = [
            'publisher' => 1,
            'user' => 1
        ];
        $url = $this->router->generate('api_1_get_publishermanagers');
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

    public function testGetPublisherManagerAction()
    {
        $url = $this->router->generate('api_1_get_publishermanager', ['id'=> 1]);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutPublisherManagerAction()
    {
        $content = [
            'publisher' => 1,
            'user' => 5
        ];
        $url = $this->router->generate('api_1_put_publishermanager', ['id' => 550]);
        $this->client->request(
            'PUT',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $response = $this->client->getResponse();
        var_dump($response->getContent());
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testPatchPublisherManagerAction()
    {
        $content = [
            'user' => 2
        ];
        $url = $this->router->generate('api_1_patch_publishermanager', ['id' => 1]);
        $this->client->request(
            'PATCH',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $response = $this->client->getResponse();
        var_dump($response->getContent());
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testDeletePublisherManagerAction()
    {
        $url = $this->router->generate('api_1_delete_publishermanager', ['id' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        var_dump($response->getContent());
        $this->assertEquals(204, $response->getStatusCode());
    }
}
