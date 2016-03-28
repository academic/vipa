<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class JournalArticleAuthorRestControllerTest extends BaseTestCase
{
    public function testGetAuthorsAction()
    {
        $routeParameters = $this->getRouteParams([
            'journalId' => 1,
            'articleId' => 1,
        ]);
        $url = $this->router->generate('api_1_article_get_authors', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
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
        $routeParameters = $this->getRouteParams([
            'journalId' => 1,
            'articleId' => 1,
        ]);
        $url = $this->router->generate('api_1_article_post_author', $routeParameters);
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

    public function testGetAuthorAction()
    {
        $routeParameters = $this->getRouteParams([
            'journalId' => 1,
            'id' => 1,
            'articleId' => 1,
        ]);
        $url = $this->router->generate('api_1_article_get_author', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
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
        $routeParameters = $this->getRouteParams([
            'journalId' => 1,
            'id' => 550,
            'articleId' => 1,
        ]);
        $url = $this->router->generate('api_1_article_put_author', $routeParameters);
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
        $routeParameters = $this->getRouteParams([
            'journalId' => 1,
            'id' => 1,
            'articleId' => 1,
        ]);
        $url = $this->router->generate('api_1_article_patch_author', $routeParameters);
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

    public function testDeleteAuthorAction()
    {
        $routeParameters = $this->getRouteParams([
            'id' => 1,
            'journalId' => 1,
            'articleId' => 1,
        ]);
        $url = $this->router->generate('api_1_article_delete_author', $routeParameters);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
