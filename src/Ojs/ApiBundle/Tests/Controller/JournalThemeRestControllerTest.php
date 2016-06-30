<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class JournalThemeRestControllerTest extends ApiBaseTestCase
{
    public function __construct()
    {
    }

    public function testGetJournalThemesAction()
    {
        $routeParameters = $this->getRouteParams(['journalId' => 1]);
        $url = $this->router->generate('api_1_get_themes', $routeParameters);
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
        $routeParameters = $this->getRouteParams(['journalId' => 1]);
        $url = $this->router->generate('api_1_post_theme', $routeParameters);
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
        $routeParameters = $this->getRouteParams(['journalId' => 1, 'id'=> 1]);
        $url = $this->router->generate('api_1_get_theme', $routeParameters);
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
        $routeParameters = $this->getRouteParams(['journalId' => 1,'id' => 550]);
        $url = $this->router->generate('api_1_put_theme', $routeParameters);
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
        $routeParameters = $this->getRouteParams(['journalId' => 1,'id' => 1]);
        $url = $this->router->generate('api_1_patch_theme', $routeParameters);
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
        $routeParameters = $this->getRouteParams(['journalId' => 1,'id' => 1]);
        $url = $this->router->generate('api_1_delete_theme', $routeParameters);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
