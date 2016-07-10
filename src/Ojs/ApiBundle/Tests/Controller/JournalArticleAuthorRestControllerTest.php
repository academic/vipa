<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class JournalArticleAuthorRestControllerTest extends ApiBaseTestCase
{
    public function testNewAuthorsAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/article/1/authors/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetAuthorsAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/article/1/authors?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostAuthorAction()
    {
        $content = [
            'author' => [
                'orcid' => 'orcid-phpunit',
                'translations' => [
                    'tr' => [
                        'biography' => 'Hello biography tr',
                    ],
                    'en' => [
                        'biography' => 'Hello biography en',
                    ],
                ],
                'title' => 1,
                'firstName' => 'Behram',
                'lastName' => 'ÇELEN',
                'middleName' => 'Middle Name',
                'phone' => '1234567890',
                'firstNameTransliterated' => '',
                'middleNameTransliterated' => '',
                'lastNameTransliterated' => '',
                'initials' => '',
                'email' => 'behramcelen@gmail.com',
                'address' => '',
                'institution' => null,
                'country' => 225,
                'authorDetails' => 'hello',
            ],
            'authorOrder' => 3,
        ];
        $this->client->request(
            'POST',
            '/api/v1/journal/1/article/1/authors?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetAuthorAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/journal/1/article/1/authors/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutAuthorAction()
    {
        $content = [
            'author' => [
                'orcid' => 'orcid-phpunit put',
                'translations' => [
                    'tr' => [
                        'biography' => 'Hello biography tr put',
                    ],
                    'en' => [
                        'biography' => 'Hello biography en put',
                    ],
                ],
                'title' => 1,
                'firstName' => 'Behram put',
                'lastName' => 'ÇELEN put',
                'middleName' => 'Middle Name put',
                'phone' => '1234567890',
                'firstNameTransliterated' => ' put',
                'middleNameTransliterated' => ' put',
                'lastNameTransliterated' => ' put',
                'initials' => '',
                'email' => 'behramcelen+put@gmail.com',
                'address' => '',
                'institution' => null,
                'country' => 225,
                'authorDetails' => 'hello put',
            ],
            'authorOrder' => 3,
        ];
        $this->client->request(
            'PUT',
            '/api/v1/journal/1/article/1/authors/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchAuthorAction()
    {
        $content = [
            'author' => [
                'orcid' => 'orcid-phpunit patch',
                'translations' => [
                    'tr' => [
                        'biography' => 'Hello biography tr patch',
                    ],
                    'en' => [
                        'biography' => 'Hello biography en patch',
                    ],
                ],
                'title' => 1,
            ],
            'authorOrder' => 2,
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/journal/1/article/1/authors/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeleteAuthorAction()
    {
        $entityId = $this->sampleObjectLoader->loadArticleAuthor();
        $this->client->request(
            'DELETE',
            '/api/v1/journal/1/article/1/authors/'.$entityId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
