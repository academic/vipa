<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class JournalBoardRestControllerTest extends ApiBaseTestCase
{
    public function testNewJournalBoardAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/boards/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetJournalBoardsAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/boards?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostJournalBoardAction()
    {
        $content = [
            'translations' => [
                'tr' => [
                    'name' => 'PHPUnit Test Name Field en - POST',
                    'description' => 'PHPUnit Test Description Field en - POST',
                ]
            ],
            'boardOrder' => '1',
        ];
        $this->client->request(
            'POST',
            '/api/v1/journal/1/boards?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetJournalBoardAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/boards/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutJournalBoardAction()
    {
        $content = [
            'translations' => [
                'tr' => [
                    'name' => 'PHPUnit Test Name Field en - PUT',
                    'description' => 'PHPUnit Test Description Field en - PUT',
                ]
            ],
            'boardOrder' => '1',
        ];
        $this->client->request(
            'PUT',
            '/api/v1/journal/1/boards/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchJournalBoardAction()
    {
        $content = [
            'translations' => [
                'tr' => [
                    'name' => 'PHPUnit Test Name Field en - PATCH',
                ]
            ],
            'boardOrder' => '20',
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/journal/1/boards/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeleteJournalBoardAction()
    {
        $entityId = $this->sampleObjectLoader->loadBoard();
        $this->client->request(
            'DELETE',
            '/api/v1/journal/1/boards/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
