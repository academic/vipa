<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class JournalArticleRestControllerTest extends ApiBaseTestCase
{
    public function testNewJournalArticleAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/articles/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetJournalArticlesAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/articles?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostJournalArticleAction()
    {
        $content = [
            'translations' => [
                'tr' => [
                    'title' => 'PHPUnit Test Title Field en - POST',
                    'keywords' => ['PHPUnit Test Keywords Field en - POST', 'New Keyword'],
                    'abstract' => 'PHPUnit Test Abstract Field en - POST'
                ]
            ],
            'titleTransliterated' => 'PHPUnit Test Title Transliterated Field en - POST',
            'doi' => 'PHPUnit Test Doi Field en - POST',
            'pubdate' => '29-10-2015',
            'pubdateSeason' => 8,
            'firstPage' => 11,
            'lastPage' => 999,
            'status' => 1,
            'uri' => 'http://phpunittest.com',
            'abstractTransliterated' => 'PHPUnit Test Abstract Transliterated Field en - POST',
            'articleType' => 4,
            'submissionDate' => '22-10-2015',
            'viewCount' => 4,
            'downloadCount' => 5,
        ];
        $this->client->request(
            'POST',
            '/api/v1/journal/1/articles?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetJournalArticleAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/articles/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutJournalArticleAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field en - PUT',
                    'keywords' => ['PHPUnit Test Keywords Field en - PUT'],
                    'abstract' => 'PHPUnit Test Abstract Field en - PUT'
                ]
            ],
            'titleTransliterated' => 'PHPUnit Test Title Transliterated Field en - PUT',
            'doi' => 'PHPUnit Test Doi Field en - POST',
            'otherId' => 'PHPUnit Test Other ID Field en - PUT',
            'pubdate' => '29-10-2015',
            'pubdateSeason' => 8,
            'firstPage' => 11,
            'lastPage' => 999,
            'uri' => 'http://phpunittest.com',
            'abstractTransliterated' => 'PHPUnit Test Abstract Transliterated Field en - PUT',
            'articleType' => 2,
            'submissionDate' => '22-10-2015',
            'viewCount' => 4,
            'downloadCount' => 5,
        ];
        $this->client->request(
            'PUT',
            '/api/v1/journal/1/articles/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchJournalArticleAction()
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
            '/api/v1/journal/1/articles/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeleteJournalArticleAction()
    {
        $entityId = $this->sampleObjectLoader->loadArticle();
        $this->client->request(
            'DELETE',
            '/api/v1/journal/1/articles/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
