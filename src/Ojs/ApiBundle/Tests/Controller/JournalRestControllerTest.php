<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class JournalRestControllerTest extends BaseTestCase
{
    public function testGetJournalsAction()
    {
        $url = $this->router->generate('api_1_get_journals');
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetJournalAction()
    {
        $url = $this->router->generate('api_1_get_journal', ['id'=> 1]);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewJournalAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field - POST',
                    'subtitle' => 'PHPUnit Test Subtitle Field - POST',
                    'description' => 'PHPUnit Test Description Field - POST',
                    'titleAbbr' => 'PHPUnit Test Title Abbr Field - POST'
                ]
            ],
            ''
        ];
        $url = $this->router->generate('api_1_get_contacttypes');
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

    public function testPutJournalAction()
    {
        $content = [];
        $content['translations'] = [
            'en' => [
                'name' => 'PHPUnit Test Name Field - Put',
                'description' => 'PHPUnit Test Description Field - Put'
            ],
            'tr' => [
                'name' => 'PHPUnit Test Name Field tr - Put',
                'description' => 'PHPUnit Test Description Field tr - Put'
            ],
        ];
        $url = $this->router->generate('api_1_put_contacttype', ['id' => 550]);
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

    public function testPatchJournalAction()
    {
        $content = [];
        $content['translations'] = [
            'tr' => [
                'name' => 'PHPUnit Test Name Field tr - Patch',
                'description' => 'PHPUnit Test Description Field tr - Patch'
            ],
        ];
        $url = $this->router->generate('api_1_patch_contacttype', ['id' => 1]);
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

    public function testDeleteJournalAction()
    {
        $url = $this->router->generate('api_1_delete_contacttype', ['id' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
