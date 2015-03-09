<?php
/**
 * Date: 8.12.14
 * Time: 11:53
 */

namespace Ojs\SiteBundle\Tests\Controller;


use Ojs\Common\Tests\BaseTestCase;

class SitemapControllerTest extends BaseTestCase
{
    const JOURNAL = 'acta-medica';
    const INSTITUTION = 'anatoliamedica';
    const SUBJECT = 'guc-ve-enerji';

    public function testIndex()
    {
        $this->assertTrue($this->isAccessible(['ojs_index_sitemap']));
    }

    public function testJournals()
    {
        $this->assertTrue($this->isAccessible(['ojs_journals_sitemap', ['_format' => 'xml','institution'=>self::INSTITUTION]]));
    }


    public function testJournal()
    {
        $this->assertTrue($this->isAccessible(['ojs_journal_sitemap', ['_format' => 'xml', 'journal' => self::JOURNAL,'institution'=>self::INSTITUTION]]));
    }

    public function testArticles()
    {
        $this->assertTrue($this->isAccessible(['ojs_articles_sitemap', ['_format' => 'xml', 'journal' => self::JOURNAL, 'institution'=>self::INSTITUTION]]));
    }

    public function testIssues()
    {
        $this->assertTrue($this->isAccessible(['ojs_issues_sitemap', ['_format' => 'xml', 'journal' => self::JOURNAL,'institution'=>self::INSTITUTION]]));
    }

    public function testLast()
    {
        $this->assertTrue($this->isAccessible(['ojs_last_issue_sitemap', ['_format' => 'xml', 'journal' => self::JOURNAL,'institution'=>self::INSTITUTION]]));
    }

    public function testInstitution()
    {
        $this->assertTrue($this->isAccessible(['ojs_institution_sitemap', ['_format' => 'xml', 'institution' => self::INSTITUTION,'institution'=>self::INSTITUTION]]));
    }

    public function testInstitutions()
    {
        $this->assertTrue($this->isAccessible(['ojs_institutions_sitemap', ['_format' => 'xml']]));
    }

    public function testSubjects()
    {
        $this->assertTrue($this->isAccessible(['ojs_subjects_sitemap', ['_format' => 'xml']]));
    }

    public function testSubject()
    {
        $this->assertTrue($this->isAccessible(['ojs_subject_sitemap', ['_format' => 'xml', 'subject' => self::SUBJECT,'institution'=>self::INSTITUTION]]));
    }
}
 