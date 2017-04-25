<?php

namespace Vipa\SiteBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class ApplicationControllerTest extends BaseTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = $this->client;
        $crawler = $client->request('GET', $url);
        $this->assertStatusCode(200, $client);
    }

    public function urlProvider()
    {
        return array(
            array('/apply/journal'),
            array('/apply/publisher'),
            array('/apply/journal/success'),
            array('/apply/publisher/success'),
        );
    }
}