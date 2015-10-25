<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class JournalSectionRestControllerTest extends BaseTestCase
{
    public function __construct()
    {
    }

    public function testGetJournalSectionsAction()
    {
        $url = $this->router->generate('api_1_get_sections', ['journalId' => 1]);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewJournalSectionAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field en - POST',
                ]
            ],
            'allowIndex' => true,
            'hideTitle' => false
        ];
        $url = $this->router->generate('api_1_post_section', ['journalId' => 1]);
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

    public function testGetJournalSectionAction()
    {
        $url = $this->router->generate('api_1_get_section', ['journalId' => 1, 'id'=> 1]);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutJournalSectionAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field en - PUT',
                ]
            ],
            'allowIndex' => true,
            'hideTitle' => false
        ];
        $url = $this->router->generate('api_1_put_section', ['journalId' => 1,'id' => 550]);
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

    public function testPatchJournalSectionAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field en - PATCH',
                ]
            ]
        ];
        $url = $this->router->generate('api_1_patch_section', ['journalId' => 1,'id' => 1]);
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

    public function testDeleteJournalSectionAction()
    {
        $url = $this->router->generate('api_1_delete_section', ['id' => 1,'journalId' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
