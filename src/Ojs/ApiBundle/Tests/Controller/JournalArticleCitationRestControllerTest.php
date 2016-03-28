<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class JournalArticleCitationRestControllerTest extends BaseTestCase
{
    public function testGetAuthorsAction()
    {
        $routeParameters = $this->getRouteParams([
            'journalId' => 1,
            'articleId' => 1,
        ]);
        $url = $this->router->generate('api_1_article_get_citations', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }


    public function testPostAuthorAction()
    {
        $content = [
            'raw' => 'Hello raw citation POST',
            'type' => 1,
            'orderNum' => 3,
        ];
        $routeParameters = $this->getRouteParams([
            'journalId' => 1,
            'articleId' => 1,
        ]);
        $url = $this->router->generate('api_1_article_post_citation', $routeParameters);
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
        $url = $this->router->generate('api_1_article_get_citation', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutAuthorAction()
    {
        $content = [
            'raw' => 'Hello raw citation PUT',
            'type' => 2,
            'orderNum' => 2,
        ];
        $routeParameters = $this->getRouteParams([
            'journalId' => 1,
            'id' => 550,
            'articleId' => 1,
        ]);
        $url = $this->router->generate('api_1_article_put_citation', $routeParameters);
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
            'raw' => 'Hello raw citation PATCH',
        ];
        $routeParameters = $this->getRouteParams([
            'journalId' => 1,
            'id' => 1,
            'articleId' => 1,
        ]);
        $url = $this->router->generate('api_1_article_patch_citation', $routeParameters);
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
        $url = $this->router->generate('api_1_article_delete_citation', $routeParameters);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
