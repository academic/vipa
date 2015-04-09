<?php
/**
 * Date: 21.11.14
 * Time: 15:07
 * Devs: [
 *   ]
 */

$set = new \h4cc\AliceFixturesBundle\Fixtures\FixtureSet([
    'seed' => 2,
    'order' => 2
]);

$set->addFile(__DIR__ . '/lang.yml', 'yaml');

$set->addFile(__DIR__ . '/institution_type.yml', 'yaml');
$set->addFile(__DIR__ . '/institution.yml', 'yaml');

$set->addFile(__DIR__ . '/subject.yml', 'yaml');

$set->addFile(__DIR__ . '/journal.yml', 'yaml');
$set->addFile(__DIR__ . '/journal_section.yml', 'yaml');

$set->addFile(__DIR__ . '/issue.yml', 'yaml');

$set->addFile(__DIR__ . '/article.yml', 'yaml');
//$set->addFile(__DIR__ . '/article_attribute.yml', 'yaml'); //Article Attribute
$set->addFile(__DIR__ . '/author.yml', 'yaml');
$set->addFile(__DIR__ . '/article_author.yml', 'yaml'); //Article Author
$set->addFile(__DIR__ . '/file.yml', 'yaml');
$set->addFile(__DIR__ . '/article_file.yml', 'yaml'); //Article File

$set->addFile(__DIR__ . '/citation.yml', 'yaml');
//$set->addFile(__DIR__ . '/citation_setting.yml', 'yaml'); //Citation Settings

$set->addFile(__DIR__ . '/contact.yml', 'yaml');
$set->addFile(__DIR__ . '/contact_type.yml', 'yaml'); //Contact Types
//$set->addFile(__DIR__ . '/journal_contact.yml', 'yaml'); //Journal Contacts


$set->addFile(__DIR__ . '/theme.yml', 'yaml'); //themes
$set->addFile(__DIR__ . '/journal_licence.yml', 'yaml'); //licences
$set->addFile(__DIR__ . '/journal_index.yml', 'yaml'); // indexes

return $set;