<?php

namespace Ojstr\ManagerBundle\Tests\Controller;

class AdminControllerTest extends \Ojstr\Common\Helper\TestHelper {

    public function testAdminDashboard() {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));
        $this->client->request('GET', '/admin/dashboard');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->logIn('demo_editor', array('ROLE_EDITOR'));
        $this->client->request('GET', '/admin/dashboard');
        $this->assertFALSE($this->client->getResponse()->isSuccessful());
    }

}
