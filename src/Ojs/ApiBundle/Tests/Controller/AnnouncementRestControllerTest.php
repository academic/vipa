<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class AnnouncementRestControllerTest extends ApiBaseTestCase
{
    public function testGetAnnouncementsAction()
    {
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_announcements', $routeParameters);
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
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_get_announcement', $routeParameters);
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
        $routeParameters = $this->getRouteParams(['id' => 550]);
        $url = $this->router->generate('api_1_put_announcement', $routeParameters);
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
        $routeParameters = $this->getRouteParams(['id' => 1]);
        $url = $this->router->generate('api_1_patch_announcement', $routeParameters);
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
        $routeParameters = $this->getRouteParams(['id' => 1]);
        $url = $this->router->generate('api_1_delete_announcement', $routeParameters);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
