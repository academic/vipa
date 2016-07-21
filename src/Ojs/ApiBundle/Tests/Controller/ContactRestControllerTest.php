<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class ContactRestControllerTest extends ApiBaseTestCase
{
    public function testNewArticleTypeAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/contacts/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetContactsAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/contacts?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostContactAction()
    {
        $content = [
            'title' => 1,
            'fullName' => 'PHPUnit Test Full Name Field en - POST',
            'address' => 'PHPUnit Test Address Field en - POST',
            'phone' => 'PHPUnit Test Phone Field en - POST',
            'email' => 'PHPUnit Test Email Field en - POST',
            'tags' => ['phpunit'],
            'contactType' => 4,
            'journal' => 1,
            'country' => 216,
        ];
        $this->client->request(
            'POST',
            '/api/v1/contacts?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetContactAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/contacts/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutContactAction()
    {
        $content = [
            'title' => 1,
            'fullName' => 'PHPUnit Test Full Name Field en - PUT',
            'address' => 'PHPUnit Test Address Field en - PUT',
            'phone' => 'PHPUnit Test Phone Field en - PUT',
            'email' => 'PHPUnit Test Email Field en - PUT',
            'tags' => ['phpunit', 'PUT'],
            'contactType' => 4,
            'journal' => 1,
            'country' => 216,
        ];
        $this->client->request(
            'PUT',
            '/api/v1/contacts/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchContactAction()
    {
        $content = [
            'address' => 'PHPUnit Test Address Field en - PUT',
            'email' => 'PHPUnit Test Email Field en - PUT',
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/contacts/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeleteContactAction()
    {
        $announcementId = $this->sampleObjectLoader->loadContact();
        $this->client->request(
            'DELETE',
            '/api/v1/contacts/'.$announcementId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
