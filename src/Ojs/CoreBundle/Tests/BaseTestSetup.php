<?php
namespace Ojs\CoreBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Client;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;

abstract class BaseTestSetup extends WebTestCase
{
    /** @var  Client */
    protected $client;

    /** @var  EntityManager */
    protected $em;
    
    protected function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $baseUrl = static::$kernel->getContainer()->getParameter('base_host');
        $this->client = static::makeClient(array(),array('HTTP_HOST' => $baseUrl));
    }

    protected function logIn($username = 'admin', $password = 'admin')
    {
        $this->client->setServerParameter('PHP_AUTH_USER',$username);
        $this->client->setServerParameter('PHP_AUTH_PW',$password);
    }

    protected function tearDown()
    {
        $this->em->close();
        unset($this->client, $this->em);
    }
}