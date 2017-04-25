<?php

namespace Vipa\ApiBundle\Tests\Controller;

use Vipa\ApiBundle\Tests\ApiBaseTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class JournalIssueRestControllerTest extends ApiBaseTestCase
{
    private $sampleFile = __DIR__.'/../../../CoreBundle/Tests/Resources/pdf/pdf-sample.pdf';
    private $sampleIssueCover = 'http://lorempixel.com/200/300/';
    private $sampleIssueHeader = 'http://lorempixel.com/960/200/';
    private $sampleFileEncoded;
    private $sampleIssueCoverEncoded;
    private $sampleIssueHeaderEncoded;

    public function __construct()
    {
        $cacheDir = __DIR__.'/../../../../../app/cache/';
        $issueCacheDir = $cacheDir.'api_issue/';
        if(!is_dir($cacheDir) || !is_dir($issueCacheDir)){
            mkdir($issueCacheDir, 0775, true);
        }
        if(!file_exists($issueCacheDir.'sampleFile')){
            file_put_contents($issueCacheDir.'sampleFile', file_get_contents($this->sampleFile));
        }
        if(!file_exists($issueCacheDir.'sampleIssueCover')){
            file_put_contents($issueCacheDir.'sampleIssueCover', file_get_contents($this->sampleIssueCover));
        }
        if(!file_exists($issueCacheDir.'sampleIssueHeader')){
            file_put_contents($issueCacheDir.'sampleIssueHeader', file_get_contents($this->sampleIssueHeader));
        }
        $this->sampleFileEncoded = base64_encode(file_get_contents($issueCacheDir.'sampleFile'));
        $this->sampleIssueCoverEncoded = base64_encode(file_get_contents($issueCacheDir.'sampleIssueCover'));
        $this->sampleIssueHeaderEncoded = base64_encode(file_get_contents($issueCacheDir.'sampleIssueHeader'));
    }

    public function testNewJournalIssueAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/issues/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetJournalIssuesAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/issues?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostJournalIssueAction()
    {
        $content = [
            'translations' => [
                'tr' => [
                    'title' => 'PHPUnit Test Title Field en - POST',
                    'description' => 'PHPUnit Test Description Field en - POST'
                ]
            ],
            'volume' => '1',
            'number' => '4',
            'special' => 1,
            'supplement' => 1,
            'year' => 2012,
            'datePublished' => '27-09-1994',
            'tags' => ['phpunit','post'],
            'full_file' => [
                'filename' => 'samplefile.pdf',
                'encoded_content' => $this->sampleFileEncoded
            ],
            'cover' => [
                'filename' => 'sampleIssueCover.jpg',
                'encoded_content' => $this->sampleIssueCoverEncoded,
            ],
            'header' => [
                'filename' => 'sampleIssueHeader.jpg',
                'encoded_content' => $this->sampleIssueHeaderEncoded,
            ],
        ];
        $this->client->request(
            'POST',
            '/api/v1/journal/1/issues?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetJournalIssueAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/issues/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutJournalIssueAction()
    {
        $content = [
            'translations' => [
                'tr' => [
                    'title' => 'PHPUnit Test Title Field en - PUT',
                    'description' => 'PHPUnit Test Description Field en - PUT'
                ]
            ],
            'volume' => '1',
            'number' => '4',
            'special' => 1,
            'supplement' => 1,
            'year' => 2012,
            'datePublished' => '27-09-1994',
            'tags' => ['phpunit','post'],
            'full_file' => [
                'filename' => 'samplefile.pdf',
                'encoded_content' => $this->sampleFileEncoded
            ],
            'cover' => [
                'filename' => 'sampleIssueCover.jpg',
                'encoded_content' => $this->sampleIssueCoverEncoded,
            ],
            'header' => [
                'filename' => 'sampleIssueHeader.jpg',
                'encoded_content' => $this->sampleIssueHeaderEncoded,
            ],
        ];
        $this->client->request(
            'PUT',
            '/api/v1/journal/1/issues/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchJournalIssueAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field en - PATCH',
                ]
            ],
            'number' => 3,
            'volume' => 1,
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/journal/1/issues/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeleteJournalIssueAction()
    {
        $entityId = $this->sampleObjectLoader->loadIssue();
        $this->client->request(
            'DELETE',
            '/api/v1/journal/1/issues/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testGetAddArticleAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/issues/1/add/article/1/section/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }
}
