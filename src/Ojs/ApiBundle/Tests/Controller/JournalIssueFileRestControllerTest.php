<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class JournalIssueFileRestControllerTest extends ApiBaseTestCase
{
    private $sampleIssueFile = 'http://www.cbu.edu.zm/downloads/pdf-sample.pdf';
    private $sampleIssueFileEncoded;

    public function __construct()
    {
        $cacheDir = __DIR__.'/../../../../../app/cache/';
        $issueFileCacheDir = $cacheDir.'api_issue_file/';
        if(!is_dir($cacheDir) || !is_dir($issueFileCacheDir)){
            mkdir($issueFileCacheDir, 0775, true);
        }
        if(!file_exists($issueFileCacheDir.'sampleIssueFile')){
            file_put_contents($issueFileCacheDir.'sampleIssueFile', file_get_contents($this->sampleIssueFile));
        }
        $this->sampleIssueFileEncoded = base64_encode(file_get_contents($issueFileCacheDir.'sampleIssueFile'));
    }

    public function testNewAuthorsAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/issue/1/files/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetFilesAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/issue/1/files?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostFileAction()
    {
        $content = [
            'file' => [
                'filename' => 'sampleIssueFile.pdf',
                'encoded_content' => $this->sampleIssueFileEncoded,
            ],
            'type' => 0,
            'version' => 0,
            'langCode' => 1,
            'translations' => [
                'tr' => [
                    'title' => 'PHPUnit Test Title - POST',
                ]
            ],
        ];
        $this->client->request(
            'POST',
            '/api/v1/journal/1/issue/1/files?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetFileAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/issue/1/files/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutFileAction()
    {
        $content = [
            'file' => [
                'filename' => 'sampleIssueFile.pdf',
                'encoded_content' => $this->sampleIssueFileEncoded,
            ],
            'type' => 2,
            'langCode' => 1,
            'version' => 0,
            'translations' => [
                'tr' => [
                    'title' => 'PHPUnit Test Title - PUT',
                ]
            ],
        ];
        $this->client->request(
            'PUT',
            '/api/v1/journal/1/issue/1/files/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchFileAction()
    {
        $content = [
            'translations' => [
                'tr' => [
                    'title' => 'PHPUnit Test Title - PATCH',
                ]
            ],
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/journal/1/issue/1/files/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeleteFileAction()
    {
        $entityId = $this->sampleObjectLoader->loadIssueFile();
        $this->client->request(
            'DELETE',
            '/api/v1/journal/1/issue/1/files/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
