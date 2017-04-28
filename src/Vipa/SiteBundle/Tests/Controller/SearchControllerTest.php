<?php

namespace Vipa\SiteBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class SearchControllerTest extends BaseTestCase
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
            array('/search?q=intro'),
            array('/search?q=advanced:journal.title:intro%20OR%20journal.translations.title:www'),
            array('/tags/cloud'),
            array('/search?q=tag:computer'),
        );
    }
}