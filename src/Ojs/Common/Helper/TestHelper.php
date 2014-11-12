<?php

namespace Ojs\Common\Helper;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

ini_set('session.save_handler', 'files');
ini_set('session.save_path', '/tmp');
session_start();

class TestHelper extends WebTestCase
{

    protected $client = null;
    protected $em = null;

    public function __construct()
    {
        parent::__construct();
    }

    public function setUp()
    {
        $this->client = static::createClient();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testStatus()
    {
        $this->assertEquals(true, true);
    }

    /**
     *
     * @param string $username
     * @param array  $role
     */
    protected function logIn($username = null, $role = null)
    {
        $session = $this->client->getContainer()->get('session');
        $firewall = 'main';
        $username = $username ? $username : 'admin';
        $user = $this->em->getRepository('OjsUserBundle:User')->findOneByUsername($username);
        $token = new UsernamePasswordToken($user, null, $firewall, $role ? $role : array('ROLE_SUPER_ADMIN'));
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

}
