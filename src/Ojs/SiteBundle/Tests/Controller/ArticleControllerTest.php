<?php

namespace Ojs\SiteBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class ArticleControllerTest extends BaseTestCase
{

    public function testArticlePage()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/intro/issue/1/1');
        $this->assertStatusCode(200,$client);
    }

    public function testArticleWithoutIssuePage()
    {
        $client = static::makeClient(array(),array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET','/intro/article/1');
        $this->assertStatusCode(302,$client);
    }

}