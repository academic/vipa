<?php
/**
 * Date: 15.11.14
 * Time: 23:26
 * Devs: [
 *   ]
 */

namespace Ojs\Common\Tests;


ini_set('session.save_handler', 'files');
ini_set('session.save_path', '/tmp');
session_start();

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class BaseTestCase extends WebTestCase
{
    /** @var Application */
    protected $app;

    /** @var  Client */
    protected $client;

    /** @var  EntityManager */
    protected $em;

    /** @var  Router */
    protected $router;


    public function setUp()
    {
        $this->client = $this->createClient();

        $this->app = new Application($this->client->getKernel());
        $this->app->setAutoExit(false);


        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->router = static::$kernel->getContainer()->get('router');
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
     * @param array $role
     */
    protected function logIn($username = null, $role = null)
    {
        $session = $this->client->getContainer()->get('session');
        $firewall = 'main';
        $user = $this->em->getRepository('OjsUserBundle:User')
            ->findOneByUsername($username ? $username : 'admin');
        if (!$user) {
            $_role = $this->em->getRepository('OjsUserBundle:Role')
                ->findOneByRole($role[0]);
            $user = $this->em->getRepository('OjsUserBundle:User')
                ->findOneByRole($_role);
        }
        if (!($user instanceof UserInterface))
            throw new \Exception("User not find. " . get_class($user));

        $token = new UsernamePasswordToken($user, null, $firewall, $role ? $role : array('ROLE_SUPER_ADMIN'));
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    protected function apiRequest($url, $type = 'GET', $data = [])
    {
        $this->client->request(
            $type,
            $url . '?apikey=MWFlZDFlMTUwYzRiNmI2NDU3NzNkZDA2MzEyNzJkNTE5NmJmZjkyZQ==',
            $data,
            [],
            ['HTTP_ACCEPT' => 'application/json']
        );
        /** @var Response $response */
        $response = $this->client->getResponse();
        return $response;
    }


    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }

    protected function isAccessible($params, $data = [],$type='GET')
    {
        $this->client->request(
            $type,
            call_user_func_array([$this->router, 'generate'], $params),
            $data
        );
        /** @var Response $response */
        $response = $this->client->getResponse();
        if($params ===['article_delete', ['id' => 2]] ){
            echo $response->getContent();
        }
        return $response->isSuccessful();
    }
} 