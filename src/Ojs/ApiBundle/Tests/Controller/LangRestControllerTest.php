<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class LangRestControllerTest extends ApiBaseTestCase
{
    public function testGetLangsAction()
    {
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_langs', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewLangAction()
    {
        $content = [
            'code' => 'PHPUnit',
            'name' => 'PHPUnit Name Field - POST',
            'rtl' => false,
        ];
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_langs', $routeParameters);
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

    public function testGetLangAction()
    {
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_get_lang', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutLangAction()
    {
        $content = [
            'code' => 'PHPUnit',
            'name' => 'PHPUnit Name Field - PUT',
            'rtl' => true,
        ];
        $routeParameters = $this->getRouteParams(['id'=> 550]);
        $url = $this->router->generate('api_1_put_lang', $routeParameters);
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

    public function testPatchLangAction()
    {
        $content = [
            'code' => 'PHPUnit',
            'name' => 'PHPUnit Name Field - PATCH',
        ];
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_patch_lang', $routeParameters);
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

    public function testDeleteLangAction()
    {
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_delete_lang', $routeParameters);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
