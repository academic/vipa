<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class JournalThemeRestControllerTest extends ApiBaseTestCase
{
    public function testNewJournalThemeAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/themes/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetJournalThemesAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/themes/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostJournalThemeAction()
    {
        $content = [
            'title' => 'PHPUnit Test Title Field en - POST',
            'public' => 1,
            'css' => 'body{color: red;.post{}}'
        ];
        $this->client->request(
            'POST',
            '/api/v1/journal/1/themes?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetJournalThemeAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/themes/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutJournalThemeAction()
    {
        $content = [
            'title' => 'PHPUnit Test Title Field en - PUT',
            'public' => 1,
            'css' => 'body{color: red;.post{}}'
        ];
        $this->client->request(
            'PUT',
            '/api/v1/journal/1/themes/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchJournalThemeAction()
    {
        $content = [
            'title' => 'PHPUnit Test Title Field en - PATCH'
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/journal/1/themes/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeleteJournalThemeAction()
    {
        $entityId = $this->sampleObjectLoader->loadJournalTheme();
        $this->client->request(
            'DELETE',
            '/api/v1/journal/1/themes/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
