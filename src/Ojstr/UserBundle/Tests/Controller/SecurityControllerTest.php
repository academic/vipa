<?php

namespace Ojstr\UserBundle\Tests\Controller;

use Ojs\Common\Helper\TestHelper;

class SecurityControllerTest extends TestHelper
{
    public function testRegsiter()
    {
        $client = static::createClient();
        $client->request('GET', '/register');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}
