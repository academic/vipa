<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class PersonTitleRestControllerTest extends BaseTestCase
{
    public function testGetPersonTitlesAction()
    {
        $url = $this->router->generate('api_1_get_persontitles');
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewPersonTitleAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field en - POST',
                ]
            ],
        ];
        $url = $this->router->generate('api_1_get_persontitles');
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

    public function testGetPersonTitleAction()
    {
        $url = $this->router->generate('api_1_get_persontitle', ['id'=> 1]);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutPersonTitleAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field en - PUT',
                ]
            ],
        ];
        $url = $this->router->generate('api_1_put_persontitle', ['id' => 550]);
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

    public function testPatchPersonTitleAction()
    {
        $content = [
            'translations' => [
                'tr' => [
                    'title' => 'PHPUnit Test Title Field TR - PATCH',
                ]
            ]
        ];
        $url = $this->router->generate('api_1_patch_persontitle', ['id' => 1]);
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

    public function testDeletePersonTitleAction()
    {
        $url = $this->router->generate('api_1_delete_persontitle', ['id' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
