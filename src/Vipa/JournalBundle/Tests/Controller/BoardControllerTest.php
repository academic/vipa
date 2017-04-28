<?php

namespace Vipa\JournalBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class BoardControllerTest extends BaseTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', $url);
        $this->assertStatusCode(200||301, $client);
    }

    public function urlProvider()
    {
        return array(
            array('/journal/1/board/'),
            array('/journal/1/board/new'),
            array('/journal/1/board/oto-generate'),
            array('/journal/1/board/1/show'),
            array('/journal/1/board/1/edit'),
        );
    }
}