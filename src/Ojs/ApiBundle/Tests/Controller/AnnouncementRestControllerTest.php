<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class AnnouncementRestControllerTest extends BaseTestCase
{
    public function testGetAnnouncementsAction()
    {
        $url = $this->router->generate('api_1_get_announcements');
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewAnnouncementAction()
    {
        $content = [
            'title' => 'PHPUnit Test Title Field en - POST',
            'image' => 'PHPUnit Test Image Field en - POST',
            'content' => 'PHPUnit Test Content Field en - POST',
        ];
        $url = $this->router->generate('api_1_get_announcements');
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

    public function testGetAnnouncementAction()
    {
        $url = $this->router->generate('api_1_get_announcement', ['id'=> 1]);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutAnnouncementAction()
    {
        $content = [
            'title' => 'PHPUnit Test Title Field en - PUT',
            'image' => 'PHPUnit Test Image Field en - PUT',
            'content' => 'PHPUnit Test Content Field en - PUT',
        ];
        $url = $this->router->generate('api_1_put_announcement', ['id' => 550]);
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

    public function testPatchAnnouncementAction()
    {
        $content = [
            'title' => 'PHPUnit Test Title Field en - PATCH',
        ];
        $url = $this->router->generate('api_1_patch_announcement', ['id' => 1]);
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

    public function testDeleteAnnouncementAction()
    {
        $url = $this->router->generate('api_1_delete_announcement', ['id' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
