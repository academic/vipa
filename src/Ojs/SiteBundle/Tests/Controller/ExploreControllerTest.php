<?php

namespace Ojs\SiteBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class ExploreControllerTest extends BaseTestCase
{
    const EXPLORE = 'ojs_site_explore_index';
    const EXPLORE_PUBLISHER = 'ojs_site_explore_publisher';

    public function testIndex()
    {
        $url                = $this->router->generate(self::EXPLORE);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPublisher()
    {
        $url                = $this->router->generate(self::EXPLORE_PUBLISHER);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }
}