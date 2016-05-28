<?php

namespace Ojs\SiteBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class SiteControllerTest extends BaseTestCase
{
    const INDEX = 'ojs_public_index';
    const PUBLISHER_PAGE = 'ojs_publisher_page';
    const JOURNAL_INDEX = 'ojs_journal_index';
    const JOURNAL_ARTICLES = 'ojs_journal_index_articles';
    const LAST_ARTICLES = 'ojs_last_articles_index';
    const ARCHIVE_INDEX = 'ojs_archive_index';
    const ANNOUNCEMENT_INDEX = 'ojs_announcement_index';
    const JOURNAL_SUBSCRIBE = 'ojs_journal_subscribe';
    const ISSUE_PAGE = 'ojs_issue_page';
    const JOURNAL_CONTACTS = 'ojs_journal_contacts';
    const JOURNAL_BOARD = 'ojs_journal_index_board';

    public function testIndex()
    {
        $url                = $this->router->generate(self::INDEX);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPublisherPage()
    {
        $routeParameters    = ['slug' => 'www'];
        $url                = $this->router->generate(self::PUBLISHER_PAGE,$routeParameters);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('OJS', $response->getContent());
    }

    public function testJournalIndex()
    {
        $routeParameters    = ['slug' => 'intro','publisher' => 'www'];
        $url                = $this->router->generate(self::JOURNAL_INDEX,$routeParameters);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('A journal about OJS', $response->getContent());
    }

    public function testJournalArticles()
    {
        $routeParameters    = ['slug' => 'intro','publisher' => 'www'];
        $url                = $this->router->generate(self::LAST_ARTICLES,$routeParameters);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Introduction to OJS', $response->getContent());
    }

    public function testArchiveIndex()
    {
        $routeParameters    = ['slug' => 'intro','publisher' => 'www'];
        $url                = $this->router->generate(self::ARCHIVE_INDEX,$routeParameters);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Introduction to OJS', $response->getContent());
    }

    public function testAnnouncementIndex()
    {
        $routeParameters    = ['slug' => 'intro','publisher' => 'www'];
        $url                = $this->router->generate(self::ANNOUNCEMENT_INDEX,$routeParameters);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Introduction to OJS', $response->getContent());
    }

    public function testIssuePage()
    {
        $routeParameters    = ['journal_slug' => 'intro', 'publisher' => 'www', 'id' => 1];
        $url                = $this->router->generate(self::ISSUE_PAGE,$routeParameters);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('First Issue', $response->getContent());
    }

    public function testJournalContacts()
    {
        $routeParameters    = ['slug' => 'intro','publisher' => 'www'];
        $url                = $this->router->generate(self::JOURNAL_CONTACTS,$routeParameters);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Introduction to OJS', $response->getContent());
    }

    public function testJournalBoard()
    {
        $routeParameters    = ['slug' => 'intro','publisher' => 'www'];
        $url                = $this->router->generate(self::JOURNAL_BOARD,$routeParameters);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Introduction to OJS', $response->getContent());
    }
}