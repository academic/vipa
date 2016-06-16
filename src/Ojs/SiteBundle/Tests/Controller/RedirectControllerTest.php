<?php

namespace Ojs\SiteBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class RedirectControllerTest extends BaseTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = $this->client;
        $crawler = $client->request('GET', $url);
        $this->assertStatusCode(302, $client);
    }

    public function urlProvider()
    {
        return array(
            array('/redirect/journal/1'),
            array('/redirect/article/1'),
        );
    }
}