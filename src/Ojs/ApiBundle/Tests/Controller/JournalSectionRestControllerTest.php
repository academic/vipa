<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class JournalSectionRestControllerTest extends ApiBaseTestCase
{
    public function __construct()
    {
    }

    public function testGetJournalSectionsAction()
    {
        $routeParameters = $this->getRouteParams(['journalId' => 1]);
        $url = $this->router->generate('api_1_get_sections', $routeParameters);
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
        $routeParameters = $this->getRouteParams(['journalId' => 1]);
        $url = $this->router->generate('api_1_post_section', $routeParameters);
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
        $routeParameters = $this->getRouteParams(['journalId' => 1, 'id'=> 1]);
        $url = $this->router->generate('api_1_get_section', $routeParameters);
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
        $routeParameters = $this->getRouteParams(['journalId' => 1, 'id'=> 550]);
        $url = $this->router->generate('api_1_put_section', $routeParameters);
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
        $routeParameters = $this->getRouteParams(['journalId' => 1, 'id'=> 1]);
        $url = $this->router->generate('api_1_patch_section', $routeParameters);
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
        $routeParameters = $this->getRouteParams(['journalId' => 1, 'id'=> 1]);
        $url = $this->router->generate('api_1_delete_section', $routeParameters);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
