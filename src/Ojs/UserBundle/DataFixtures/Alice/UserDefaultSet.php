<?php
/**
 * Date: 21.11.14
 * Time: 13:50
 * Devs: [
 *   ]
 */

$set = new \h4cc\AliceFixturesBundle\Fixtures\FixtureSet([
    'locale' => 'en_US',
    'seed' => 1,
    'do_drop' => false,
    'do_persist' => true,
    'order'=>1
]);

$set->addFile(__DIR__ . '/roles.yml', 'yaml');
$set->addFile(__DIR__ . '/default.yml', 'yaml');

return $set;