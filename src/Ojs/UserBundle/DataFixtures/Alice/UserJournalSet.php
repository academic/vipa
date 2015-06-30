<?php

$set = new \h4cc\AliceFixturesBundle\Fixtures\FixtureSet(
    [
        'seed' => 3,
        'order' => 3,
    ]
);

$set->addFile(__DIR__.'/journal_role.yml', 'yaml');

return $set;
