<?php
/** 
 * User: aybarscengaver
 * Date: 15.11.14
 * Time: 23:26
 * URI: www.emre.xyz
 * Devs: [
 * 'Aybars Cengaver'=>'aybarscengaver@yahoo.com',
 *   ]
 */

namespace Ojs\Common\Tests;


ini_set('session.save_handler', 'files');
ini_set('session.save_path', '/tmp');
session_start();

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class BaseTestCase extends WebTestCase {
    /** @var Application */
    protected $app;

    protected $client;

    protected $em;

    public function setup(){
        $this->client = $this->createClient();

        $this->app = new Application($this->client->getKernel());
        $this->app->setAutoExit(false);

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

    }
    public function command($command, $opts = [])
    {
        $opts['-e'] = 'test';
        $opts['-q'] = null;
        $opts['command'] = $command;
        return $this->app->run(new ArrayInput($opts));
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
        $user = $this->em->getRepository('OjsUserBundle:User')->findOneByUsername($username ? $username : 'admin');
        if(!($user instanceof UserInterface))
            throw new \Exception("User not find");
        $token = new UsernamePasswordToken($user, null, $firewall, $role ? $role : array('ROLE_SUPER_ADMIN'));
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

} 