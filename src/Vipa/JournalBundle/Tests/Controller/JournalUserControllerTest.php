<?php

namespace Vipa\JournalBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class JournalUserControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/user');

        $this->assertStatusCode(200, $client);
    }

    public function testAddUser()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/user/add');

        $this->assertStatusCode(200, $client);
    }

    public function testNewUser()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/user/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=journal_new_user]')->form();
        $form['journal_new_user[username]'] = 'JournalUserPhpunit';
        $form['journal_new_user[password]'] = 'phpunitPassword';
        $form['journal_new_user[email]'] = 'journalUserNewPhpunit@vipa.io';
        $form['journal_new_user[title]'] = '1';
        $form['journal_new_user[firstName]'] = 'First Name - phpunit';
        $form['journal_new_user[lastName]'] = 'Last Name - phpunit';
        $form['journal_new_user[subjects]'] = ['2'];
        $form['journal_new_user[avatar]'] = '';
        $form['journal_new_user[country]'] = '225';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $crawler = $client->getCrawler();
        $this->assertEquals(1, $crawler->filter('.alert-success')->count());

        $form = $crawler->filter('form[name=journal_user_edit]')->form();
        $form['journal_user_edit[roles]'] = ['1'];

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'journalUserNewPhpunit@vipa.io',
            $this->client->getResponse()->getContent()
        );

    }

    public function testCreateUser()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/user/create');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=journal_new_user]')->form();
        $form['journal_new_user[username]'] = 'JournalUser2Phpunit';
        $form['journal_new_user[password]'] = 'phpunitPassword';
        $form['journal_new_user[email]'] = 'journalUser2NewPhpunit@vipa.io';
        $form['journal_new_user[title]'] = '1';
        $form['journal_new_user[firstName]'] = 'First Name - phpunit';
        $form['journal_new_user[lastName]'] = 'Last Name - phpunit';
        $form['journal_new_user[subjects]'] = ['2'];
        $form['journal_new_user[avatar]'] = '';
        $form['journal_new_user[country]'] = '225';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $crawler = $client->getCrawler();
        $this->assertEquals(1, $crawler->filter('.alert-success')->count());

        $form = $crawler->filter('form[name=journal_user_edit]')->form();
        $form['journal_user_edit[roles]'] = ['1'];

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'journalUser2NewPhpunit@vipa.io',
            $this->client->getResponse()->getContent()
        );
    }

    public function testRegisterAsAuthor()
    {
        $this->logIn();
        $client = $this->client;

        $client->request('GET', '/journal/join');

        $this->assertStatusCode(200, $client);

        $client->request('GET', '/journal/join/1');

        $this->assertStatusCode(302, $client);

        $client->request('GET', '/journal/leave/1/role/10');

        $this->assertStatusCode(302, $client);

        $client->request('GET', '/journal/leave/1');

        $this->assertStatusCode(302, $client);
    }

    public function testJournals()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/my');

        $this->assertStatusCode(200, $client);
    }
}