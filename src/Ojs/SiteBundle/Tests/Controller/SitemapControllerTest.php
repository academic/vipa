<?php

namespace Ojs\SiteBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class SitemapControllerTest extends BaseTestCase
{
    const JOURNAL = 'acta-medica';
    const PUBLISHER = 'anatoliamedica';
    const SUBJECT = 'guc-ve-enerji';

    public function testIndex()
    {
        $this->assertTrue($this->isAccessible(['ojs_index_sitemap']));
    }

    public function testJournals()
    {
        $this->assertTrue(
            $this->isAccessible(['ojs_journals_sitemap', ['_format' => 'xml', 'publisher' => self::PUBLISHER]])
        );
    }

    public function testJournal()
    {
        $this->assertTrue(
            $this->isAccessible(
                [
                    'ojs_journal_sitemap',
                    ['_format' => 'xml', 'journal' => self::JOURNAL, 'publisher' => self::PUBLISHER],
                ]
            )
        );
    }

    public function testArticles()
    {
        $this->assertTrue(
            $this->isAccessible(
                [
                    'ojs_articles_sitemap',
                    ['_format' => 'xml', 'journal' => self::JOURNAL, 'publisher' => self::PUBLISHER],
                ]
            )
        );
    }

    public function testIssues()
    {
        $this->assertTrue(
            $this->isAccessible(
                [
                    'ojs_issues_sitemap',
                    ['_format' => 'xml', 'journal' => self::JOURNAL, 'publisher' => self::PUBLISHER],
                ]
            )
        );
    }

    public function testLast()
    {
        $this->assertTrue(
            $this->isAccessible(
                [
                    'ojs_last_issue_sitemap',
                    ['_format' => 'xml', 'journal' => self::JOURNAL, 'publisher' => self::PUBLISHER],
                ]
            )
        );
    }

    public function testPublisher()
    {
        $this->assertTrue(
            $this->isAccessible(
                [
                    'ojs_publisher_sitemap',
                    ['_format' => 'xml', 'publisher' => self::PUBLISHER],
                ]
            )
        );
    }

    public function testPublishers()
    {
        $this->assertTrue($this->isAccessible(['ojs_publishers_sitemap', ['_format' => 'xml']]));
    }

    public function testSubjects()
    {
        $this->assertTrue($this->isAccessible(['ojs_subjects_sitemap', ['_format' => 'xml']]));
    }

    public function testSubject()
    {
        $this->assertTrue(
            $this->isAccessible(
                [
                    'ojs_subject_sitemap',
                    ['_format' => 'xml', 'subject' => self::SUBJECT, 'publisher' => self::PUBLISHER],
                ]
            )
        );
    }
}
