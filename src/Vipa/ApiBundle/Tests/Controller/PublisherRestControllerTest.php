<?php

namespace Vipa\ApiBundle\Tests\Controller;

use Vipa\ApiBundle\Tests\ApiBaseTestCase;

class PublisherRestControllerTest extends ApiBaseTestCase
{
    public function testNewPublisherAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/publishers/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetPublishersAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/publishers?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetPublisherAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/publishers/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostPublisherAction()
    {
        $content = [
            'translations' => [
                $this->locale => [
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
        $this->client->request(
            'POST',
            '/api/v1/publishers?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPutPublisherAction()
    {
        $content = [
            'translations' => [
                $this->locale => [
                    'name' => 'PHPUnit Test Name Field - POST',
                    'about' => 'PHPUnit Test About Field en - POST',
                ]
            ],
            'slug' => 'phpunit-test-put',
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
        $this->client->request(
            'PUT',
            '/api/v1/publishers/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchPublisherAction()
    {
        $content = [
            'translations' => [
                $this->secondLocale => [
                    'about' => 'PHPUnit Test About Field '.$this->secondLocale.' - PATCH',
                ]
            ]
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/publishers/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeletePublisherAction()
    {
        $entityId = $this->sampleObjectLoader->loadPublisher();
        $this->client->request(
            'DELETE',
            '/api/v1/publishers/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
