<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;

/**
 * @todo new article, update article, delete article  and show article
 */
class ArticleControllerTest extends BaseTestCase
{

    public function testIndex()
    {
        $this->logIn();
        $this->assertTrue($this->isAccessible(['article']));
    }

    public function testArticleJournal()
    {
        $this->logIn();
        $this->assertTrue($this->isAccessible(['article_journal', ['journalId' => 1]]));
    }

    public function testArticleIssue()
    {
        $this->logIn();
        $this->assertTrue($this->isAccessible(['article_issue', ['issueId' => 1]]));
    }

    public function testArticleShow()
    {
        $this->logIn();
        $this->assertTrue($this->isAccessible(['article_show', ['id' => 1]]));
    }

    public function testArticleNew()
    {
        $this->logIn();
        $this->assertTrue($this->isAccessible(['article_new']));
    }

    public function testCitation()
    {
        $this->logIn();
        $this->assertTrue($this->isAccessible(['citation', ['id' => 1]]));
    }

    public function testArticleEdit()
    {
        $this->logIn();
        $this->assertTrue($this->isAccessible(['article_edit', ['id' => '1']]));
    }

    public function testArticleDelete()
    {
        $this->logIn();
        $this->assertTrue($this->isAccessible(['article_delete', ['id' => 2]],[],'DELETE',true));
    }

    public function testStatus()
    {
        $client = $this->client;
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $client->request('GET', '/admin/article/');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $client->request('GET', '/admin/article/new');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

}
