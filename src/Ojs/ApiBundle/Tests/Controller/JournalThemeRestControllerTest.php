<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class JournalThemeRestControllerTest extends BaseTestCase
{
    public function __construct()
    {
    }

    public function testGetJournalThemesAction()
    {
        $url = $this->router->generate('api_1_get_themes', ['journalId' => 1]);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewJournalThemeAction()
    {
        $content = [
            'title' => 'PHPUnit Test Title Field en - POST',
            'public' => 1,
            'css' => 'body{color: red;.post{}}'
        ];
        $url = $this->router->generate('api_1_post_theme', ['journalId' => 1]);
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

    public function testGetJournalThemeAction()
    {
        $url = $this->router->generate('api_1_get_theme', ['journalId' => 1, 'id'=> 1]);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutJournalThemeAction()
    {
        $content = [
            'title' => 'PHPUnit Test Title Field en - PUT',
            'public' => 1,
            'css' => 'body{color: red;.post{}}'
        ];
        $url = $this->router->generate('api_1_put_theme', ['journalId' => 1,'id' => 550]);
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

    public function testPatchJournalThemeAction()
    {
        $content = [
            'title' => 'PHPUnit Test Title Field en - PATCH'
        ];
        $url = $this->router->generate('api_1_patch_theme', ['journalId' => 1,'id' => 1]);
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

    public function testDeleteJournalThemeAction()
    {
        $url = $this->router->generate('api_1_delete_theme', ['id' => 1,'journalId' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
