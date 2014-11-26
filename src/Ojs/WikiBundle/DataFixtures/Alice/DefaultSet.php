<?php
/**
 * Date: 25.11.14
 * Time: 19:42
 * Devs: [
 *   ]
 */
$set = new \h4cc\AliceFixturesBundle\Fixtures\FixtureSet([
    'order' => 5
]);

$set->addFile(__DIR__ . '/page.yml', 'yaml');

return $set;