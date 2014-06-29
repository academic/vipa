<?php

namespace Ojstr\WorkflowBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase {

    public function testIndex() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/workflow');
        $this->assertTrue($crawler->filter('html:contains("")')->count() > 0);
    }

}
