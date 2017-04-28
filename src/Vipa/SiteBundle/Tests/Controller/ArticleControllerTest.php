<?php

namespace Vipa\SiteBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class ArticleControllerTest extends BaseTestCase
{
    public function testArticlePage()
    {
        $client = $this->client;
        $client->request('GET','/intro/issue/1/1');
        $this->assertStatusCode(200,$client);
    }

    public function testArticleWithoutIssuePage()
    {
        $client = $this->client;
        $client->request('GET','/intro/article/1');
        $this->assertStatusCode(302,$client);
    }

    public function testJournalArticles()
    {
        $client = $this->client;
        $client->request('GET', '/intro/articles');
        $this->assertStatusCode(200, $client);
    }

}