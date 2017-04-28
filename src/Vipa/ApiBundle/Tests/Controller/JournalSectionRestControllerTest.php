<?php

namespace Vipa\ApiBundle\Tests\Controller;

use Vipa\ApiBundle\Tests\ApiBaseTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class JournalSectionRestControllerTest extends ApiBaseTestCase
{
    public function testNewJournalSectionAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/sections/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetJournalSectionsAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/sections?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostJournalSectionAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field en - POST',
                ]
            ],
            'allowIndex' => true,
            'hideTitle' => false,
            'sectionOrder' => 8,
        ];
        $this->client->request(
            'POST',
            '/api/v1/journal/1/sections?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetJournalSectionAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/sections/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutJournalSectionAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field en - PUT',
                ]
            ],
            'allowIndex' => true,
            'hideTitle' => false,
            'sectionOrder' => 8,
        ];
        $this->client->request(
            'PUT',
            '/api/v1/journal/1/sections/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchJournalSectionAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field en - PATCH',
                ]
            ]
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/journal/1/sections/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeleteJournalSectionAction()
    {
        $entityId = $this->sampleObjectLoader->loadSection();
        $this->client->request(
            'DELETE',
            '/api/v1/journal/1/sections/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
