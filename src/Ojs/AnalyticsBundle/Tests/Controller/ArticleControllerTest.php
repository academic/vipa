<?php
/**
 * User: aybarscengaver
 * Date: 16.11.14
 * Time: 20:58
 * URI: www.emre.xyz
 * Devs: [
 * 'Aybars Cengaver'=>'aybarscengaver@yahoo.com',
 *   ]
 */

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
