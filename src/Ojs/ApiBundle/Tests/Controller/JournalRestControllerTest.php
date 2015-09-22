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
            'publisher' => 1,
            'mandatoryLang' => 1,
            'languages' => [
                1,2,5
            ],
            'periods' => [
                1,2,3
            ],
            'subjects' => [
                1,2
            ],
            'domain' => 'domain.com',
            'issn' => '2147-9488',
            'eissn' => '2147-9488',
            'founded' => 2016,
            'googleAnalyticsId' => 'Google Ana. ID',
            'country' => 2,
            'footer_text' => 'footer_text',
            'published' => 1,
            'printed' => 1,
            'slug' => 'phpunit-test',
            'tags' => ['phpunit']
        ];
        $url = $this->router->generate('api_1_get_journals');
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
        $content = [
            'translations' => [
                'en' => [
                    'title' => 'PHPUnit Test Title Field - PUT',
                    'subtitle' => 'PHPUnit Test Subtitle Field - PUT',
                    'description' => 'PHPUnit Test Description Field - PUT',
                    'titleAbbr' => 'PHPUnit Test Title Abbr Field - PUT'
                ],
                'tr' => [
                    'title' => 'PHPUnit Test Title Field TR - PUT',
                    'subtitle' => 'PHPUnit Test Subtitle Field TR - PUT',
                    'description' => 'PHPUnit Test Description Field TR - PUT',
                    'titleAbbr' => 'PHPUnit Test Title Abbr Field TR - PUT'
                ]
            ],
            'publisher' => 1,
            'mandatoryLang' => 1,
            'languages' => [
                1,2,5
            ],
            'periods' => [
                1,2,3
            ],
            'subjects' => [
                1,2
            ],
            'domain' => 'domain.com',
            'issn' => '2147-9488',
            'eissn' => '2147-9488',
            'founded' => 2016,
            'googleAnalyticsId' => 'Google Ana. ID',
            'country' => 2,
            'footer_text' => 'footer_text',
            'published' => 1,
            'printed' => 1,
            'slug' => 'phpunit-test',
            'tags' => ['phpunit']
        ];
        $url = $this->router->generate('api_1_put_journal', ['id' => 550]);
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
        $content = [
            'translations' => [
                'tr' => [
                    'title' => 'PHPUnit Test Title Field TR - PATCH',
                    'subtitle' => 'PHPUnit Test Subtitle Field TR - PATCH',
                    'description' => 'PHPUnit Test Description Field TR - PATCH',
                    'titleAbbr' => 'PHPUnit Test Title Abbr Field TR - PATCH'
                ]
            ]
        ];
        $url = $this->router->generate('api_1_patch_journal', ['id' => 1]);
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
        $url = $this->router->generate('api_1_delete_journal', ['id' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
