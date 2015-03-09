<?php
/**
 * Date: 22.11.14
 * Time: 23:21
 * Devs: [
 *   ]
 */

$set = new \h4cc\AliceFixturesBundle\Fixtures\FixtureSet([
    'order' => 4
]);

$set->addFile(__DIR__ . '/page.yml', 'yaml');

return $set;