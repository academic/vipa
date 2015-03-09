<?php
ini_set('memory_limit', '1024M');
require_once(__DIR__ . '/bootstrap.php.cache');
require_once(__DIR__ . '/AppKernel.php');


use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

$kernel = new AppKernel('test', true);
$kernel->boot();


$application = new Application($kernel);
$application->setAutoExit(false);

deleteDatabase();
executeCommand($application,"doctrine:schema:create");
executeCommand($application, "ojs:install:travis");
executeCommand($application, "h4cc_alice_fixtures:load:sets");
backupDatabase();


function deleteDatabase()
{
    $folder = __DIR__ . '/cache/test/';
    foreach (['test.db', 'test.db.bk'] AS $file) {
        if (file_exists($folder . $file)) {
            unlink($folder . $file);
        }
    }
}

function backupDatabase(){
    copy(__DIR__ . '/cache/test/test.db', __DIR__ . '/cache/test/test.db.bk');
}
function restoreDatabase(){
    copy(__DIR__ . '/cache/test/test.db.bk', __DIR__ . '/cache/test/test.db');
}

function executeCommand(Application $app,$cmd,Array $options=[]){
    $options['--env']='test';
    $options['--quiet'] = true;
    $options['command'] = $cmd;
    $app->run(new ArrayInput($options));
}
