<?php

namespace Vipa\ApiBundle\Tests\Controller;

use Vipa\ApiBundle\Tests\ApiBaseTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class JournalArticleFileRestControllerTest extends ApiBaseTestCase
{
    private $sampleArticleFile = 'http://www.cbu.edu.zm/downloads/pdf-sample.pdf';
    private $sampleArticleFileEncoded;

    public function __construct()
    {
        $cacheDir = __DIR__.'/../../../../../app/cache/';
        $articleFileCacheDir = $cacheDir.'api_article_file/';
        if(!is_dir($cacheDir) || !is_dir($articleFileCacheDir)){
            mkdir($articleFileCacheDir, 0775, true);
        }
        if(!file_exists($articleFileCacheDir.'sampleArticleFile')){
            file_put_contents($articleFileCacheDir.'sampleArticleFile', file_get_contents($this->sampleArticleFile));
        }
        $this->sampleArticleFileEncoded = base64_encode(file_get_contents($articleFileCacheDir.'sampleArticleFile'));
    }

    public function testNewAuthorsAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/article/1/files/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetFilesAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/article/1/files?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostFileAction()
    {
        $content = [
            'file' => [
                'filename' => 'sampleArticleFile.pdf',
                'encoded_content' => $this->sampleArticleFileEncoded,
            ],
            'type' => 2,
            'langCode' => 1,
            'title' => 'PHPUnit Test Title',
            'description' => 'PHPUnit Test Title',
        ];
        $this->client->request(
            'POST',
            '/api/v1/journal/1/article/1/files?apikey='.$this->apikey,
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
        $client->request('GET', '/api/v1/journal/1/article/1/files/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutFileAction()
    {
        $content = [
            'file' => [
                'filename' => 'sampleArticleFile.pdf',
                'encoded_content' => $this->sampleArticleFileEncoded,
            ],
            'type' => 2,
            'langCode' => 1,
            'title' => 'PHPUnit Test Title Put',
            'description' => 'PHPUnit Test description Put',
        ];
        $this->client->request(
            'PUT',
            '/api/v1/journal/1/article/1/files/550?apikey='. $this->apikey,
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
            'title' => 'PHPUnit Test Title Patch',
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/journal/1/article/1/files/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeleteFileAction()
    {
        $entityId = $this->sampleObjectLoader->loadArticleFile();
        $this->client->request(
            'DELETE',
            '/api/v1/journal/1/article/1/files/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
