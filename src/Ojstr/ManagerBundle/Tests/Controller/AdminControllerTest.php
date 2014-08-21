<?php

namespace Ojstr\ManagerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AdminControllerTest extends \Ojstr\Common\Helper\TestHelper {

    public function testAdminDashboard() {
        $this->logIn();
        $this->client->request('GET', '/admin/dashboard');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

}
