<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class JournalArticleRestControllerTest extends BaseTestCase
{
    private $sampleArticleHeader = 'http://lorempixel.com/960/200/';
    private $sampleArticleHeaderEncoded;

    public function __construct()
    {
        $cacheDir = __DIR__.'/../../../../../app/cache/';
        $articleCacheDir = $cacheDir.'api_article/';
        if(!is_dir($cacheDir) || !is_dir($articleCacheDir)){
            mkdir($articleCacheDir, 0775, true);
        }
        if(!file_exists($articleCacheDir.'sampleArticleHeader')){
            file_put_contents($articleCacheDir.'sampleArticleHeader', file_get_contents($this->sampleArticleHeader));
        }
        $this->sampleArticleHeaderEncoded = base64_encode(file_get_contents($articleCacheDir.'sampleArticleHeader'));
    }

    public function testGetJournalArticlesAction()
    {
        $url = $this->router->generate('api_1_get_articles', ['journalId' => 1]);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewJournalArticleAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field en - POST',
                    'subjects' => 'PHPUnit Test Subjects Field en - POST',
                    'keywords' => 'PHPUnit Test Keywords Field en - POST',
                    'abstract' => 'PHPUnit Test Abstract Field en - POST'
                ]
            ],
            'titleTransliterated' => 'PHPUnit Test Title Transliterated Field en - POST',
            'status' => 1,
            'doi' => 'PHPUnit Test Doi Field en - POST',
            'otherId' => 'PHPUnit Test Other ID Field en - POST',
            'anonymous' => 1,
            'pubdate' => '29-10-2015',
            'pubdateSeason' => 8,
            'firstPage' => 11,
            'lastPage' => 999,
            'uri' => 'http://phpunittest.com',
            'abstractTransliterated' => 'PHPUnit Test Abstract Transliterated Field en - POST',
            'articleType' => 10,
            'orderNum' => 2,
            'submissionDate' => '22-10-2015',
            'header' => [
                'filename' => rand(0,1000).'sampleArticleHeader.jpg',
                'encoded_content' => $this->sampleArticleHeaderEncoded,
            ],
        ];
        $url = $this->router->generate('api_1_post_article', ['journalId' => 1]);
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

    public function testGetJournalArticleAction()
    {
        $url = $this->router->generate('api_1_get_article', ['journalId' => 1, 'id'=> 1]);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutJournalArticleAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field en - PUT',
                    'subjects' => 'PHPUnit Test Subjects Field en - PUT',
                    'keywords' => 'PHPUnit Test Keywords Field en - PUT',
                    'abstract' => 'PHPUnit Test Abstract Field en - PUT'
                ]
            ],
            'titleTransliterated' => 'PHPUnit Test Title Transliterated Field en - PUT',
            'status' => 1,
            'doi' => 'PHPUnit Test Doi Field en - POST',
            'otherId' => 'PHPUnit Test Other ID Field en - PUT',
            'anonymous' => 1,
            'pubdate' => '29-10-2015',
            'pubdateSeason' => 8,
            'firstPage' => 11,
            'lastPage' => 999,
            'uri' => 'http://phpunittest.com',
            'abstractTransliterated' => 'PHPUnit Test Abstract Transliterated Field en - PUT',
            'articleType' => 10,
            'orderNum' => 2,
            'submissionDate' => '22-10-2015',
            'header' => [
                'filename' => rand(0,1000).'sampleArticleHeader.jpg',
                'encoded_content' => $this->sampleArticleHeaderEncoded,
            ],
        ];
        $url = $this->router->generate('api_1_put_article', ['journalId' => 1,'id' => 550]);
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

    public function testPatchJournalArticleAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field en - PATCH',
                ]
            ],
            'header' => [
                'filename' => rand(0,1000).'sampleArticleHeader.jpg',
                'encoded_content' => $this->sampleArticleHeaderEncoded,
            ],
        ];
        $url = $this->router->generate('api_1_patch_article', ['journalId' => 1,'id' => 1]);
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

    public function testDeleteJournalArticleAction()
    {
        $url = $this->router->generate('api_1_delete_article', ['id' => 1,'journalId' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
