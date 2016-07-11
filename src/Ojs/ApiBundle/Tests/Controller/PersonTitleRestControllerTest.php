<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class PersonTitleRestControllerTest extends ApiBaseTestCase
{
    public function testNewPersonTitleAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/persontitles/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetPersonTitlesAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/persontitles?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostPersonTitleAction()
    {
        $content = [
            'translations' => [
                $this->locale => [
                    'title' => 'PHPUnit Test Title Field '.$this->locale.' - POST',
                ]
            ],
        ];
        $this->client->request(
            'POST',
            '/api/v1/persontitles?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetPersonTitleAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/persontitles/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutPersonTitleAction()
    {
        $content = [
            'translations' => [
                $this->locale => [
                    'title' => 'PHPUnit Test Title Field '.$this->locale.' - PUT',
                ]
            ],
        ];
        $this->client->request(
            'PUT',
            '/api/v1/persontitles/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchPersonTitleAction()
    {
        $content = [
            'translations' => [
                $this->secondLocale => [
                    'title' => 'PHPUnit Test Title Field '.$this->secondLocale.' - PATCH',
                ]
            ]
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/persontitles/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeletePersonTitleAction()
    {
        $entityId = $this->sampleObjectLoader->loadPersonTitle();
        $this->client->request(
            'DELETE',
            '/api/v1/persontitles/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
