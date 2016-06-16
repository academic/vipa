<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class CitationControllerTest extends BaseTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', $url);
        $this->assertStatusCode(200, $client);
    }

    public function urlProvider()
    {
        return array(
            array('/journal/1/article/1/citation/'),
            array('/journal/1/article/1/citation/1/show'),
            array('/journal/1/article/1/citation/new'),
            array('/journal/1/article/1/citation/1/edit'),
        );
    }
}