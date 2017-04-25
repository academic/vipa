<?php

namespace Vipa\ApiBundle\Tests\Controller;

use Vipa\ApiBundle\Tests\ApiBaseTestCase;

class AnnouncementRestControllerTest extends ApiBaseTestCase
{
    public function testNewAnnouncementsAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/announcements/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetAnnouncementsAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/announcements?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostAnnouncementAction()
    {
        $content = [
            'title' => 'PHPUnit Test Title Field en - POST',
            'image' => 'PHPUnit Test Image Field en - POST',
            'content' => 'http://phpunit.com',
        ];
        $this->client->request(
            'POST',
            '/api/v1/announcements?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetAnnouncementAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/announcements/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutAnnouncementAction()
    {
        $content = [
            'title' => 'PHPUnit Test Title Field en - PUT',
            'image' => 'PHPUnit Test Image Field en - PUT',
            'content' => 'http://phpunit.com',
        ];
        $this->client->request(
            'PUT',
            '/api/v1/announcements/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchAnnouncementAction()
    {
        $content = [
            'title' => 'PHPUnit Test Title Field en - PATCH',
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/announcements/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeleteAnnouncementAction()
    {
        $announcementId = $this->sampleObjectLoader->loadAnnouncement();
        $this->client->request(
            'DELETE',
            '/api/v1/announcements/'.$announcementId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
