<?php

$set = new \h4cc\AliceFixturesBundle\Fixtures\FixtureSet([
    'order' => 1
]);

$set->addFile(__DIR__ . '/countries.yml', 'yaml');

$set->addFile(__DIR__ . '/cities.yml', 'yaml');

return $set;