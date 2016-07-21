<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class PeriodRestControllerTest extends ApiBaseTestCase
{
    public function testNewPeriodAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/periods/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetPeriodsAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/periods/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostPeriodAction()
    {
        $content = [
            'translations' => [
                $this->locale => [
                    'period' => 'PHPUnit Test Period Field '.$this->locale.' - POST',
                ]
            ],
        ];
        $this->client->request(
            'POST',
            '/api/v1/periods?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetPeriodAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/periods/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutPeriodAction()
    {
        $content = [
            'translations' => [
                $this->locale => [
                    'period' => 'PHPUnit Test Period Field '.$this->locale.' - PUT',
                ]
            ],
        ];
        $this->client->request(
            'PUT',
            '/api/v1/periods/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchPeriodAction()
    {
        $content = [
            'translations' => [
                $this->secondLocale => [
                    'period' => 'PHPUnit Test Period Field '.$this->secondLocale.' - PATCH',
                ]
            ]
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/periods/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeletePeriodAction()
    {
        $entityId = $this->sampleObjectLoader->loadPeriod();
        $this->client->request(
            'DELETE',
            '/api/v1/periods/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
