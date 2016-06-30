<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class JournalBoardRestControllerTest extends ApiBaseTestCase
{
    public function __construct()
    {
    }

    public function testGetJournalBoardsAction()
    {
        $routeParameters = $this->getRouteParams(['journalId' => 1]);
        $url = $this->router->generate('api_1_get_boards', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewJournalBoardAction()
    {
        $content = [
            'name' => 'PHPUnit Test Name Field en - POST',
            'description' => 'PHPUnit Test Description Field en - POST',
        ];
        $routeParameters = $this->getRouteParams(['journalId' => 1]);
        $url = $this->router->generate('api_1_post_board', $routeParameters);
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

    public function testGetJournalBoardAction()
    {
        $routeParameters = $this->getRouteParams(['journalId' => 1, 'id'=> 1]);
        $url = $this->router->generate('api_1_get_board', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutJournalBoardAction()
    {
        $content = [
            'name' => 'PHPUnit Test Name Field en - PUT',
            'description' => 'PHPUnit Test Description Field en - PUT',
        ];
        $routeParameters = $this->getRouteParams(['journalId' => 1,'id' => 550]);
        $url = $this->router->generate('api_1_put_board', $routeParameters);
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

    public function testPatchJournalBoardAction()
    {
        $content = [
            'name' => 'PHPUnit Test Name Field en - PATCH',
        ];
        $routeParameters = $this->getRouteParams(['journalId' => 1,'id' => 1]);
        $url = $this->router->generate('api_1_patch_board', $routeParameters);
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

    public function testDeleteJournalBoardAction()
    {
        $routeParameters = $this->getRouteParams(['id' => 1,'journalId' => 1]);
        $url = $this->router->generate('api_1_delete_board', $routeParameters);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
