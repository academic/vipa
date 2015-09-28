<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class IndexRestControllerTest extends BaseTestCase
{
    public function testGetIndexesAction()
    {
        $url = $this->router->generate('api_1_get_indexes');
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewIndexesAction()
    {
        $content = [
            'name' => 'PHPUnitTest Name Field - POST',
            'logo' => 'http://logo.com/POST',
            'status' => true,
        ];
        $url = $this->router->generate('api_1_get_indexes');
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

    public function testGetIndexsAction()
    {
        $url = $this->router->generate('api_1_get_indexes', ['id'=> 1]);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutIndexesAction()
    {
        $content = [
            'name' => 'PHPUnitTest Name Field - PUT',
            'logo' => 'http://logo.com/PUT',
            'status' => true,
        ];
        $url = $this->router->generate('api_1_put_indexes', ['id' => 550]);
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

    public function testPatchIndexesAction()
    {
        $content = [
            'name' => 'PHPUnitTest Name Field - PATCH',
        ];
        $url = $this->router->generate('api_1_patch_indexes', ['id' => 1]);
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

    public function testDeleteIndexesAction()
    {
        $url = $this->router->generate('api_1_delete_indexes', ['id' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
