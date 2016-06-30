<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class JournalIssueRestControllerTest extends ApiBaseTestCase
{
    private $sampleFile = 'http://www.cbu.edu.zm/downloads/pdf-sample.pdf';
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

    public function testGetJournalIssuesAction()
    {
        $routeParameters = $this->getRouteParams(['journalId' => 1]);
        $url = $this->router->generate('api_1_get_issues', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewJournalIssueAction()
    {
        $content = [
            'translations' => [
                'en' => [
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
            'published' => 1,
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
        $routeParameters = $this->getRouteParams(['journalId' => 1]);
        $url = $this->router->generate('api_1_post_issue', $routeParameters);
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

    public function testGetJournalIssueAction()
    {
        $routeParameters = $this->getRouteParams(['journalId' => 1, 'id'=> 1]);
        $url = $this->router->generate('api_1_get_issue', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutJournalIssueAction()
    {
        $content = [
            'translations' => [
                'en' => [
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
            'published' => 1,
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
        $routeParameters = $this->getRouteParams(['journalId' => 1,'id' => 550]);
        $url = $this->router->generate('api_1_put_issue', $routeParameters);
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

    public function testPatchJournalIssueAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field en - PATCH',
                ]
            ],
            'header' => [
                'filename' => 'sampleIssueHeader.jpg',
                'encoded_content' => $this->sampleIssueHeaderEncoded,
            ],
        ];
        $routeParameters = $this->getRouteParams(['journalId' => 1,'id' => 1]);
        $url = $this->router->generate('api_1_patch_issue', $routeParameters);
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

    public function testDeleteJournalIssueAction()
    {
        $routeParameters = $this->getRouteParams(['journalId' => 1,'id' => 1]);
        $url = $this->router->generate('api_1_delete_issue', $routeParameters);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testGetAddArticleAction()
    {
        $routeParameters = $this->getRouteParams([
            'journalId' => 1,
            'issueId'   => 1,
            'articleId' => 1,
            'sectionId' => 1,
        ]);
        $url = $this->router->generate('api_1_get_add_article', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }
}
