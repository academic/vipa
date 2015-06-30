<?php

namespace Ojs\AnalyticsBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

class ArticleControllerTest extends BaseTestCase
{
    public function testArticleViews()
    {
        $this->client->request('GET', $this->router->generate('analytics_views_article_all'));
        /** @var Response $response */
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testArticleDownloads()
    {
        $this->client->request('GET', $this->router->generate('analytics_downloads_article'));
        /** @var Response $response */
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }
}
