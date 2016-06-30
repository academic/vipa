<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;
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

    public function testGetFilesAction()
    {
        $routeParameters = $this->getRouteParams([
            'journalId' => 1,
            'articleId' => 1,
        ]);
        $url = $this->router->generate('api_1_article_get_files', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
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
        $routeParameters = $this->getRouteParams([
            'journalId' => 1,
            'articleId' => 1,
        ]);
        $url = $this->router->generate('api_1_article_post_file', $routeParameters);
        $this->client->request(
            'POST',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $response = $this->client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testGetFileAction()
    {
        $routeParameters = $this->getRouteParams([
            'journalId' => 1,
            'id' => 1,
            'articleId' => 1,
        ]);
        $url = $this->router->generate('api_1_article_get_file', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
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
        $routeParameters = $this->getRouteParams([
            'journalId' => 1,
            'id' => 550,
            'articleId' => 1,
        ]);
        $url = $this->router->generate('api_1_article_put_file', $routeParameters);
        $this->client->request(
            'PUT',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $response = $this->client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testPatchFileAction()
    {
        $content = [
            'file' => [
                'filename' => 'sampleArticleFile.pdf',
                'encoded_content' => $this->sampleArticleFileEncoded,
            ],
            'title' => 'PHPUnit Test Title Patch',
        ];
        $routeParameters = $this->getRouteParams([
            'journalId' => 1,
            'id' => 1,
            'articleId' => 1,
        ]);
        $url = $this->router->generate('api_1_article_patch_file', $routeParameters);
        $this->client->request(
            'PATCH',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testDeleteFileAction()
    {
        $routeParameters = $this->getRouteParams([
            'id' => 1,
            'journalId' => 1,
            'articleId' => 1,
        ]);
        $url = $this->router->generate('api_1_article_delete_file', $routeParameters);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
