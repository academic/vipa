<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class LangRestControllerTest extends BaseTestCase
{
    public function testGetLangsAction()
    {
        $url = $this->router->generate('api_1_get_langs');
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
        $url = $this->router->generate('api_1_get_langs');
        $this->client->request(
            'POST',
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

    public function testGetLangAction()
    {
        $url = $this->router->generate('api_1_get_lang', ['id'=> 1]);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        var_dump($response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutLangAction()
    {
        $content = [
            'code' => 'PHPUnit',
            'name' => 'PHPUnit Name Field - PUT',
            'rtl' => true,
        ];
        $url = $this->router->generate('api_1_put_lang', ['id' => 550]);
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

    public function testPatchLangAction()
    {
        $content = [
            'code' => 'PHPUnit',
            'name' => 'PHPUnit Name Field - PATCH',
        ];
        $url = $this->router->generate('api_1_patch_lang', ['id' => 1]);
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

    public function testDeleteLangAction()
    {
        $url = $this->router->generate('api_1_delete_lang', ['id' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        var_dump($response->getContent());
        $this->assertEquals(204, $response->getStatusCode());
    }
}
