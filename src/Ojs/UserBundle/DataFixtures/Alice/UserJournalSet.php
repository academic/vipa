<?php
/**
 * User: aybarscengaver
 * Date: 21.11.14
 * Time: 13:50
 * URI: www.emre.xyz
 * Devs: [
 * 'Aybars Cengaver'=>'aybarscengaver@yahoo.com',
 *   ]
 */

$set = new \h4cc\AliceFixturesBundle\Fixtures\FixtureSet([
    'seed' => 3,
    'order'=>3
]);

$set->addFile(__DIR__ . '/journal_role.yml', 'yaml');

return $set;