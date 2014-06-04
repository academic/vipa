<?php

namespace Ojstr\RefBaseBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/refbase');
        
        $this->assertTrue($crawler->filter('html')->count() > 0);
    }
}
