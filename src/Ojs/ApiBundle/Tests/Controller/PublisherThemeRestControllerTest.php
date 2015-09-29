<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class PublisherThemeRestControllerTest extends BaseTestCase
{
    public function testGetPublisherThemesAction()
    {
        $url = $this->router->generate('api_1_get_publisherthemes');
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
        $url = $this->router->generate('api_1_get_publisherthemes');
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
        $url = $this->router->generate('api_1_get_publishertheme', ['id'=> 1]);
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
        $url = $this->router->generate('api_1_put_publishertheme', ['id' => 550]);
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
        $url = $this->router->generate('api_1_patch_publishertheme', ['id' => 1]);
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
        $url = $this->router->generate('api_1_delete_publishertheme', ['id' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
