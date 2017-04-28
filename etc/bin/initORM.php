#!/usr/bin/env php
<?php

function executing($cmd) {
    echo $cmd.PHP_EOL;
    system($cmd);
}

function runInit($env) {
    executing('php app/console --env='. $env .' doctrine:database:drop --force');
    executing('php app/console --env='. $env .' --no-interaction vipa:install');
    executing('php app/console --env='. $env .' vipa:install:samples');
    executing('php app/console --env='. $env .' h4cc_alice_fixtures:load:sets');
    executing('php app/console --env='. $env .' vipa:normalize:translatable:objects');
    executing('php app/console --env='. $env .' vipa:normalize:journal:article:types');
    executing('php app/console --env='. $env .' vipa:mail:events:sync --sync-desc');
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