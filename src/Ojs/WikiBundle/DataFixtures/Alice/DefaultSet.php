<?php
/**
 * User: aybarscengaver
 * Date: 25.11.14
 * Time: 19:42
 * URI: www.emre.xyz
 * Devs: [
 * 'Aybars Cengaver'=>'aybarscengaver@yahoo.com',
 *   ]
 */
$set = new \h4cc\AliceFixturesBundle\Fixtures\FixtureSet([
    'order' => 5
]);

$set->addFile(__DIR__ . '/page.yml', 'yaml');

return $set;