<?php

namespace Ojstr\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProxyControllerTest extends WebTestCase {
    public function testStatus() {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $this->client->request('GET', '/parents');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
