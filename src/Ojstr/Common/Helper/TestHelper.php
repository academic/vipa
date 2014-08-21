<?php

namespace Ojstr\Common\Helper;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

ini_set('session.save_handler', 'files');
ini_set('session.save_path', 'app/cache');
session_start();

class TestHelper extends WebTestCase {

    protected $client = null;

    function __construct() {
        parent::__construct();
    }

    public function setUp() {
        $this->client = static::createClient();
    }

    public function testStatus() {
        $this->assertEquals(TRUE, TRUE);
    }

    /**
     * 
     * @param string $username
     * @param array $role
     */
    protected function logIn($username = NULL, $role = NULL) {
        $session = $this->client->getContainer()->get('session');
        $firewall = 'main';
        $token = new UsernamePasswordToken($username ? $username : 'admin', null, $firewall, $role ? $role : array('ROLE_SUPER_ADMIN'));
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

}
