<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class ArticleTypeRestControllerTest extends BaseTestCase
{
    public function testGetArticleTypesAction()
    {
        $url = $this->router->generate('api_1_get_articletypes');
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewArticleTypeAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'name' => 'PHPUnit Test Name Field en - POST',
                    'description' => 'PHPUnit Test Description Field en - POST',
                ]
            ],
        ];
        $url = $this->router->generate('api_1_get_articletypes');
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

    public function testGetArticleTypeAction()
    {
        $url = $this->router->generate('api_1_get_articletype', ['id'=> 1]);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutArticleTypeAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'name' => 'PHPUnit Test Name Field en - PUT',
                    'description' => 'PHPUnit Test Description Field en - PUT',
                ]
            ],
        ];
        $url = $this->router->generate('api_1_put_articletype', ['id' => 550]);
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

    public function testPatchArticleTypeAction()
    {
        $content = [
            'translations' => [
                'tr' => [
                    'name' => 'PHPUnit Test Name Field TR - PATCH',
                ]
            ]
        ];
        $url = $this->router->generate('api_1_patch_articletype', ['id' => 1]);
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

    public function testDeleteArticleTypeAction()
    {
        $url = $this->router->generate('api_1_delete_articletype', ['id' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
