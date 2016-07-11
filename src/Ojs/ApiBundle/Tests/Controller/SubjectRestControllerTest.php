<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class SubjectRestControllerTest extends ApiBaseTestCase
{
    public function testNewSubjectAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/subjects/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetSubjectsAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/subjects?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostSubjectAction()
    {
        $content = [
            'translations' => [
                $this->locale => [
                    'subject' => 'PHPUnit Test Subject Field '.$this->locale.' - POST',
                    'description' => 'PHPUnit Test Description Field '.$this->locale.' - POST',
                ]
            ],
            'tags' => ['phpunit'],
        ];
        $this->client->request(
            'POST',
            '/api/v1/subjects?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetSubjectAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/subjects/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutSubjectAction()
    {
        $content = [
            'translations' => [
                $this->locale => [
                    'subject' => 'PHPUnit Test Subject Field '.$this->locale.' - PUT',
                    'description' => 'PHPUnit Test Description Field '.$this->locale.' - PUT',
                ]
            ],
            'tags' => ['phpunit'],
        ];
        $this->client->request(
            'PUT',
            '/api/v1/subjects/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchSubjectAction()
    {
        $content = [
            'translations' => [
                $this->secondLocale => [
                    'subject' => 'PHPUnit Test Subject Field '.$this->secondLocale.' - PATCH',
                    'description' => 'PHPUnit Test Description Field '.$this->secondLocale.' - PATCH',
                ]
            ]
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/subjects/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeleteSubjectAction()
    {
        $entityId = $this->sampleObjectLoader->loadSubject();
        $this->client->request(
            'DELETE',
            '/api/v1/subjects/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
