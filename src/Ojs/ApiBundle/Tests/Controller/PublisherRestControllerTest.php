<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class PublisherRestControllerTest extends BaseTestCase
{
    public function testGetPublishersAction()
    {
        $url = $this->router->generate('api_1_get_publishers');
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
            'name' => 'PHPUnit Test Name Field - POST',
            'translations' => [
                'en' => [
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
            'city' => 'Ankara'

        ];
        $url = $this->router->generate('api_1_get_publishers');
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
            'name' => 'PHPUnit Test Name Field - PUT',
            'translations' => [
                'en' => [
                    'about' => 'PHPUnit Test About Field en - PUT',
                ]
            ],
            'slug' => 'phpunit-test',
            'tags' => ['phpunit'],
            'publisher_type' => 3,
            'address' => 'PHPUnit Test Adress Field - PUT',
            'phone' => '12345678910',
            'fax' => '987654321',
            'email' => 'behram.celen@okulbilisim.com',
            'wiki' => 'http://www.wiki.com',
            'domain' => 'behram.org',
            'verified' => 1,
            'country' => 216,
            'city' => 'Ankara'

        ];
        $url = $this->router->generate('api_1_put_publisher', ['id' => 550]);
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
        $url = $this->router->generate('api_1_patch_publisher', ['id' => 1]);
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
        $url = $this->router->generate('api_1_delete_publisher', ['id' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
