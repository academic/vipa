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
    protected static $isFirstTest = false;
    
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
        $this->runConsole("ojs:mail:events:sync", array("--sync-desc"=> null));
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