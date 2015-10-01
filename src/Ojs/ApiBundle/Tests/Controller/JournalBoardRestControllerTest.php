<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class JournalBoardRestControllerTest extends BaseTestCase
{
    public function __construct()
    {
    }

    public function testGetJournalBoardsAction()
    {
        $url = $this->router->generate('api_1_get_boards', ['journalId' => 1]);
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
        $url = $this->router->generate('api_1_post_board', ['journalId' => 1]);
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
        $url = $this->router->generate('api_1_get_board', ['journalId' => 1, 'id'=> 1]);
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
        $url = $this->router->generate('api_1_put_board', ['journalId' => 1,'id' => 550]);
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
        $url = $this->router->generate('api_1_patch_board', ['journalId' => 1,'id' => 1]);
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
        $url = $this->router->generate('api_1_delete_board', ['id' => 1,'journalId' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
