<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class PublisherRestControllerTest extends ApiBaseTestCase
{
    public function testGetPublishersAction()
    {
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_publishers', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetPublisherAction()
    {
        $url = $this->router->generate('api_1_get_publisher', ['id'=> 1]);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewPublisherAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'name' => 'PHPUnit Test Name Field - POST',
                    'about' => 'PHPUnit Test About Field en - POST',
                ]
            ],
            'slug' => 'phpunit-test',
            'tags' => ['phpunit'],
            'publisherType' => 3,
            'address' => 'PHPUnit Test Adress Field - POST',
            'phone' => '12345678910',
            'fax' => '987654321',
            'email' => 'behram.celen@okulbilisim.com',
            'wiki' => 'http://www.wiki.com',
            'domain' => 'behram.org',
            'verified' => 1,
            'country' => 216,
            'status' => 0,

        ];
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_publishers', $routeParameters);
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

    public function testPutPublisherAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'name' => 'PHPUnit Test Name Field - POST',
                    'about' => 'PHPUnit Test About Field en - POST',
                ]
            ],
            'slug' => 'phpunit-test',
            'tags' => ['phpunit'],
            'publisherType' => 3,
            'address' => 'PHPUnit Test Adress Field - POST',
            'phone' => '12345678910',
            'fax' => '987654321',
            'email' => 'behram.celen@okulbilisim.com',
            'wiki' => 'http://www.wiki.com',
            'domain' => 'behram.org',
            'verified' => 1,
            'country' => 216,
            'status' => 0,

        ];
        $routeParameters = $this->getRouteParams(['id' => 550]);
        $url = $this->router->generate('api_1_put_publisher', $routeParameters);
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

    public function testPatchPublisherAction()
    {
        $content = [
            'translations' => [
                'tr' => [
                    'about' => 'PHPUnit Test About Field TR - PATCH',
                ]
            ]
        ];
        $routeParameters = $this->getRouteParams(['id' => 1]);
        $url = $this->router->generate('api_1_patch_publisher', $routeParameters);
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

    public function testDeletePublisherAction()
    {
        $routeParameters = $this->getRouteParams(['id' => 1]);
        $url = $this->router->generate('api_1_delete_publisher', $routeParameters);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
