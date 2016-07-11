<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class JournalRestControllerTest extends ApiBaseTestCase
{
    public function testNewJournalAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journals/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetJournalsAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journals?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetJournalAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journals/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostJournalAction()
    {
        $content = [
            'translations' => [
                $this->locale => [
                    'title' => 'PHPUnit Test Title Field - POST',
                    'subtitle' => 'PHPUnit Test Subtitle Field - POST',
                    'description' => 'PHPUnit Test Description Field - POST',
                    'titleAbbr' => 'PHPUnit Test Title Abbr Field - POST'
                ]
            ],
            'publisher' => 1,
            'mandatoryLang' => 1,
            'languages' => [
                1,2,5
            ],
            'periods' => [
                1,2,3
            ],
            'subjects' => [
                1,2
            ],
            'domain' => 'domain.com',
            'issn' => '',
            'eissn' => '',
            'founded' => 2016,
            'googleAnalyticsId' => 'Google Ana. ID',
            'country' => 2,
            'footer_text' => 'footer_text',
            'slug' => 'phpunit-test',
            'tags' => ['phpunit']
        ];
        $this->client->request(
            'POST',
            '/api/v1/journals?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPutJournalAction()
    {
        $content = [
            'translations' => [
                $this->locale => [
                    'title' => 'PHPUnit Test Title Field - PUT',
                    'subtitle' => 'PHPUnit Test Subtitle Field - PUT',
                    'description' => 'PHPUnit Test Description Field - PUT',
                    'titleAbbr' => 'PHPUnit Test Title Abbr Field - PUT'
                ],
                $this->secondLocale => [
                    'title' => 'PHPUnit Test Title Field '.$this->secondLocale.' - PUT',
                    'subtitle' => 'PHPUnit Test Subtitle Field '.$this->secondLocale.' - PUT',
                    'description' => 'PHPUnit Test Description Field '.$this->secondLocale.' - PUT',
                    'titleAbbr' => 'PHPUnit Test Title Abbr Field '.$this->secondLocale.' - PUT'
                ]
            ],
            'publisher' => 1,
            'mandatoryLang' => 1,
            'languages' => [
                1,2,5
            ],
            'periods' => [
                1,2,3
            ],
            'subjects' => [
                1,2
            ],
            'domain' => 'domain.com',
            'issn' => '',
            'eissn' => '',
            'founded' => 2016,
            'googleAnalyticsId' => 'Google Ana. ID',
            'country' => 2,
            'footer_text' => 'footer_text',
            'slug' => 'phpunit-test',
            'tags' => ['phpunit']
        ];
        $this->client->request(
            'PUT',
            '/api/v1/journals/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchJournalAction()
    {
        $content = [
            'translations' => [
                $this->secondLocale => [
                    'title' => 'PHPUnit Test Title Field '.$this->secondLocale.' - PATCH',
                    'subtitle' => 'PHPUnit Test Subtitle Field '.$this->secondLocale.' - PATCH',
                    'description' => 'PHPUnit Test Description Field '.$this->secondLocale.' - PATCH',
                    'titleAbbr' => 'PHPUnit Test Title Abbr Field '.$this->secondLocale.' - PATCH'
                ]
            ]
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/journals/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeleteJournalAction()
    {
        $entityId = $this->sampleObjectLoader->loadJournal();
        $this->client->request(
            'DELETE',
            '/api/v1/journals/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
