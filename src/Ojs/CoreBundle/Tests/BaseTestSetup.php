<?php

namespace Ojs\CoreBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Client;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\ArrayInput;
use Doctrine\DBAL\Driver\PDOSqlite\Driver as SqliteDriver;

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
     * @var bool
     */
    protected static $isFirstTest = true;
    
    protected function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $baseUrl = static::$kernel->getContainer()->getParameter('base_host');
        $this->client = static::makeClient(array(),array('HTTP_HOST' => $baseUrl));

        $this->app = new Application($this->client->getKernel());
        $this->app->setAutoExit(false);

        if (!$this->useCachedDatabase()) {
            $this->databaseInit();
        }
    }

    /**
     * Initialize database
     */
    protected function databaseInit()
    {
        $this->runConsole("doctrine:schema:drop", array("--force" => true));
        $this->runConsole("ojs:install");
        $this->runConsole("ojs:install:samples");
        $this->runConsole("h4cc_alice_fixtures:load:sets");
        $this->runConsole("ojs:normalize:translatable:objects");
    }

    /**
     * Use cached database for testing or return false if not
     */
    protected function useCachedDatabase()
    {
        $container = static::$kernel->getContainer();
        $om = $this->em;
        $connection = $om->getConnection();

        if ($connection->getDriver() instanceOf SqliteDriver) {
            $params = $connection->getParams();
            $name = isset($params['path']) ? $params['path'] : $params['dbname'];
            $filename = pathinfo($name, PATHINFO_BASENAME);
            $backup = $container->getParameter('kernel.cache_dir') . '/'.$filename;

            // The first time we won't use the cached version
            if (self::$isFirstTest) {
                self::$isFirstTest = false;
                return false;
            }

            self::$isFirstTest = false;

            // Regenerate not-existing database
            if (!file_exists($name)) {
                @unlink($backup);
                return false;
            }

            $om->flush();
            $om->clear();

            // Copy backup to database
            if (!file_exists($backup)) {
                copy($name, $backup);
            }

            copy($backup, $name);
            return true;
        }

        return false;
    }

    /**
     * @param $command
     * @param array $options
     * @return integer
     */
    protected function runConsole($command, Array $options = array())
    {
        $options["--env"] = "test";
        $options["--quiet"] = null;
        $options["--no-interaction"] = null;
        $options = array_merge($options, array('command' => $command));

        return $this->app->run(new ArrayInput($options));
    }

    protected function logIn($username = 'admin', $password = 'admin')
    {
        $this->client->setServerParameter('PHP_AUTH_USER', $username);
        $this->client->setServerParameter('PHP_AUTH_PW', $password);
    }

    protected function tearDown()
    {
        $this->em->close();
        unset($this->client, $this->em);
    }
}