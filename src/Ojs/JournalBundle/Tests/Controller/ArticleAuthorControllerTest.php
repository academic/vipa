<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class ArticleControllerTest extends BaseTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = $this->client;
        $crawler = $client->request('GET', $url,array(),array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ));
        $this->assertStatusCode(200, $client);
    }

    public function urlProvider()
    {
        return array(
            array('/journal/1/article/1/author'),
            array('/journal/1/article/1/author/1/show'),
            array('/journal/1/article/1/author/new'),
            array('/journal/1/article/1/author/'),
        );
    }
}
