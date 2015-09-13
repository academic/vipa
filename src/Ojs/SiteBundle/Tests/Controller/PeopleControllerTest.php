<?php

namespace Ojs\SiteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PeopleControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/index');
    }

    public function testShow()
    {
        $client = static::createClient();

        $client->request('GET', '/show');
    }
}
