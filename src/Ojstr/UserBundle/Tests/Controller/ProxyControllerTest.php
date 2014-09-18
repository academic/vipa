<?php

namespace Ojstr\UserBundle\Tests\Controller;

use \Ojstr\Common\Helper\TestHelper;

class ProxyControllerTest extends TestHelper
{
    public function testStatus()
    {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $this->client->request('GET', '/manager/proxy/clients');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
