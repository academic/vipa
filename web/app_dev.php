<?php
#https://github.com/SymBB/symbb_sandbox/commit/85587894baa7c15975bb86950366509a0850963a
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1'))
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

//require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/ClassLoader/ApcClassLoader.php';
$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
$cachedLoader = new \Symfony\Component\ClassLoader\ApcClassLoader(sha1(__FILE__), $loader);

// register the cached class loader
$cachedLoader->register();

// deactivate the original, non-cached loader if it was registered previously
$loader->unregister();
Debug::enable();

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);

$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
