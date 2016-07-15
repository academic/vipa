<?php

namespace Ojs\CoreBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Ojs\CoreBundle\Service\SampleObjectLoader;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Client;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\ArrayInput;
use Doctrine\DBAL\Driver\PDOSqlite\Driver as SqliteDriver;
use Symfony\Component\Routing\RouterInterface;

abstract class BaseTestSetup extends WebTestCase
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var  Client
     */
    protected $client;

    /**
     * @var  EntityManager
     */
    protected $em;

    /**
     * @var  RouterInterface
     */
    protected $router;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $secondLocale;

    /**
     * @var SampleObjectLoader
     */
    protected $sampleObjectLoader;

    /**
     * @var bool
     */
    protected static $isFirstTest = true;

    protected function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $container = static::$kernel->getContainer();

        $this->em = $container->get('doctrine')->getManager();
        $this->locale = $container->getParameter('locale');
        $this->secondLocale = array_values(array_diff($container->getParameter('locale_support'), [$this->locale]))[0];
        $this->router = $container->get('router');
        $this->sampleObjectLoader = $container->get('ojs_core.sample.object_loader');
        $baseUrl = $container->getParameter('base_host');
        $this->client = static::makeClient(array(),array('HTTP_HOST' => $baseUrl));

        $this->app = new Application($this->client->getKernel());
        $this->app->setAutoExit(false);
    }

    protected function logIn($username = 'admin', $password = 'admin')
    {
        $this->client->setServerParameter('PHP_AUTH_USER', $username);
        $this->client->setServerParameter('PHP_AUTH_PW', $password);
    }

    protected function generateToken($var)
    {
        return $this->client->getContainer()->get('security.csrf.token_manager')->getToken($var);
    }

    protected function tearDown()
    {
        $this->em->close();
        unset($this->client, $this->em);
    }
}