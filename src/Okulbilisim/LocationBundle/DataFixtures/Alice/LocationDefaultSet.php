<?php

$set = new \h4cc\AliceFixturesBundle\Fixtures\FixtureSet([
    'order' => 1
]);

$set->addFile(__DIR__ . '/location.yml', 'yaml');


return $set;