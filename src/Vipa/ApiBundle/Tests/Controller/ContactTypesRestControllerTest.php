<?php

namespace Vipa\ApiBundle\Tests\Controller;

use Vipa\ApiBundle\Tests\ApiBaseTestCase;

class ContactTypesRestControllerTest extends ApiBaseTestCase
{
    public function testNewContacttypeAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/contacttypes/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetContacttypesAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/contacttypes?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetContacttypeAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/contacttypes/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostContacttypeAction()
    {
        $content = [];
        $content['translations'] = [
            $this->locale => [
                'name' => 'PHPUnit Test Name Field - POST',
                'description' => 'PHPUnit Test Description Field - POST'
            ]
        ];
        $this->client->request(
            'POST',
            '/api/v1/contacttypes?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPutContacttypeAction()
    {
        $content = [];
        $content['translations'] = [
            $this->locale => [
                'name' => 'PHPUnit Test Name Field - Put',
                'description' => 'PHPUnit Test Description Field - Put'
            ],
            $this->secondLocale => [
                'name' => 'PHPUnit Test Name Field '.$this->secondLocale.' - Put',
                'description' => 'PHPUnit Test Description Field '.$this->secondLocale.' - Put'
            ],
        ];
        $this->client->request(
            'PUT',
            '/api/v1/contacttypes/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchContacttypeAction()
    {
        $content = [];
        $content['translations'] = [
            $this->locale => [
                'name' => 'PHPUnit Test Name Field '.$this->locale.' - Patch',
                'description' => 'PHPUnit Test Description Field '.$this->locale.' - Patch'
            ],
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/contacttypes/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeleteContacttypeAction()
    {
        $announcementId = $this->sampleObjectLoader->loadContactType();
        $this->client->request(
            'DELETE',
            '/api/v1/contacttypes/'.$announcementId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
