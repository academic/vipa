<?php
/**
 * Date: 8.12.14
 * Time: 11:53
 */

namespace Ojs\SiteBundle\Tests\Controller;


use Ojs\Common\Tests\BaseTestCase;

class RssControllerTest extends BaseTestCase
{
    const JOURNAL = 1;
    const INSTITUTION = 1;
    const SUBJECT = 1;

    public function testIndex()
    {
        $this->assertTrue($this->isAccessible(['ojs_index_rss']));
    }

    public function testJournals()
    {
        $this->assertTrue($this->isAccessible(['ojs_journals_rss', ['_format' => 'xml']]));
    }


    public function testJournal()
    {
        $this->assertTrue($this->isAccessible(['ojs_journal_rss', ['_format' => 'xml', 'journal' => self::JOURNAL]]));
    }

    public function testArticles()
    {
        $this->assertTrue($this->isAccessible(['ojs_articles_rss', ['_format' => 'xml', 'journal' => self::JOURNAL]]));
    }

    public function testIssues()
    {
        $this->assertTrue($this->isAccessible(['ojs_issues_rss', ['_format' => 'xml', 'journal' => self::JOURNAL]]));
    }

    public function testLast()
    {
        $this->assertTrue($this->isAccessible(['ojs_last_issue_rss', ['_format' => 'xml', 'journal' => self::JOURNAL]]));
    }

    public function testInstitution()
    {
        $this->assertTrue($this->isAccessible(['ojs_institution_rss', ['_format' => 'xml', 'institution' => self::INSTITUTION]]));
    }

    public function testInstitutions()
    {
        $this->assertTrue($this->isAccessible(['ojs_institutions_rss', ['_format' => 'xml']]));
    }

    public function testSubjects()
    {
        $this->assertTrue($this->isAccessible(['ojs_subjects_rss', ['_format' => 'xml']]));
    }

    public function testSubject()
    {
        $this->assertTrue($this->isAccessible(['ojs_subject_rss', ['_format' => 'xml', 'subject' => self::SUBJECT]]));
    }
}
 