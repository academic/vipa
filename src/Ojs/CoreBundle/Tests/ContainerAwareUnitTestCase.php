<?php

namespace Ojs\CoreBundle\Tests;

use Symfony\Component\HttpKernel\Kernel;

require_once __DIR__.'/../../../../app/AppKernel.php';

class ContainerAwareUnitTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var Kernel */
    protected static $kernel;
    protected static $container;

    public static function setUpBeforeClass()
    {
        self::$kernel = new \AppKernel('dev', true);
        self::$kernel->boot();

        self::$container = self::$kernel->getContainer();
    }

    public function get($serviceId)
    {
        return self::$kernel->getContainer()->get($serviceId);
    }
}
