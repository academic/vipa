#!/usr/bin/env php
<?php

function executing($cmd) {
    echo $cmd.PHP_EOL;
    system($cmd);
}

function runInit($env) {
    executing('php app/console --env='. $env .' doctrine:database:drop --force');
    executing('php app/console --env='. $env .' ojs:install');
    executing('php app/console --env='. $env .' ojs:install:samples');
    executing('php app/console --env='. $env .' h4cc_alice_fixtures:load:sets');
    executing('php app/console --env='. $env .' ojs:normalize:translatable:objects');
    executing('php app/console --env='. $env .' ojs:mail:events:sync --sync-desc');
}

array_shift($argv);
if (!isset($argv[0])) {
    exit(<<<EOF
   Init doctrine:orm
   Specify the Env: eg test
EOF
 );
}

runInit(array_shift($argv));