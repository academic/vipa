<?php

namespace Vipa\SiteBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class ShortLinkControllerTest extends BaseTestCase
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
            array('/a/1'),
            array('/doi/10.5281/zenodo.14791'),
        );
    }
}