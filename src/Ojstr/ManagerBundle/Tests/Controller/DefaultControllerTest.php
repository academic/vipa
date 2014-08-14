<?php

namespace Ojstr\ManagerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
class DefaultControllerTest extends \Ojstr\Common\Helper\TestHelper{

    public function testStatus() {
        $this->assertEquals(TRUE, TRUE);
    }

    public function testAdminDashboard() {
        $client = static::createClient();
        $container = static::$kernel->getContainer();

        $client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie($container->get('session')->getName(), true));
        $token = new UsernamePasswordToken('admin', null, 'secured_area', array('ROLE_ADMIN'));
        $container->get('security.context')->setToken($token);
        $container->get('session')->set('_security_secured_area', serialize($token));

        $client->request('GET', '/admin/dashboard');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

}
