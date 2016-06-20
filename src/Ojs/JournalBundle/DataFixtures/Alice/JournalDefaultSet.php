<?php
$set = new \h4cc\AliceFixturesBundle\Fixtures\FixtureSet(
    [
        'seed' => 1,
        'order' => 2,
    ]
);

$set->addFile(__DIR__.'/subject.yml', 'yaml');
$set->addFile(__DIR__.'/system_setting.yml', 'yaml');
$set->addFile(__DIR__.'/lang.yml', 'yaml');
$set->addFile(__DIR__ .'/publisher_type.yml', 'yaml');
$set->addFile(__DIR__ .'/publisher.yml', 'yaml');
$set->addFile(__DIR__ .'/publisher_design.yml', 'yaml');
$set->addFile(__DIR__ .'/publisher_theme.yml', 'yaml');
$set->addFile(__DIR__ .'/institution.yml', 'yaml');
$set->addFile(__DIR__.'/journal.yml', 'yaml');
$set->addFile(__DIR__.'/section.yml', 'yaml');
$set->addFile(__DIR__.'/issue.yml', 'yaml');
$set->addFile(__DIR__.'/article.yml', 'yaml');
$set->addFile(__DIR__.'/author.yml', 'yaml');
$set->addFile(__DIR__.'/article_author.yml', 'yaml'); //Article Author
$set->addFile(__DIR__.'/article_file.yml', 'yaml'); //Article File
$set->addFile(__DIR__.'/citation.yml', 'yaml');
$set->addFile(__DIR__.'/admin_journal_theme.yml', 'yaml'); //themes
$set->addFile(__DIR__.'/submission_checklist.yml', 'yaml'); //submission checklist
$set->addFile(__DIR__.'/index.yml', 'yaml'); // indexes

//$set->addFile(__DIR__ . '/article_attribute.yml', 'yaml'); //Article Attribute

return $set;
