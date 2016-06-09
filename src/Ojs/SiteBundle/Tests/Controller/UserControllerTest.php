<?php

namespace Ojs\SiteBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class UserControllerTest extends BaseTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = $this->client;

        $this->logIn();
        $crawler = $client->request('GET', $url);
        $this->assertStatusCode(200, $client);
    }

    public function urlProvider()
    {
        return array(
            array('/@admin'),
            array('/user/update'),
            array('/user/custom_field'),
            array('/user/custom_field/create'),
            array('/user/connected_accounts'),
            array('/user/connected_accounts/add'),
        );
    }
}