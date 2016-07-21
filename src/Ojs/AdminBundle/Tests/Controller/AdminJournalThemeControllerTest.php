<?php

namespace Ojs\AdminBundle\Tests\Controller;

use Ojs\AdminBundle\Entity\AdminJournalTheme;
use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class AdminJournalThemeControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/journal-theme/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/journal-theme/new');

        $this->assertStatusCode(200, $client);
        
        $form = $crawler->filter('form[name=admin_journal_theme]')->form();
        $form['admin_journal_theme[title]'] = 'Theme Title - phpunit';
        $form['admin_journal_theme[css]'] = 'Theme css';
        $form['admin_journal_theme[public]'] = '1';
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $this->assertContains(
            'Theme Title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/journal-theme/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/journal-theme/1/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=admin_journal_theme]')->form();
        $form['admin_journal_theme[title]'] = 'Theme Edit Title - phpunit';
        $form['admin_journal_theme[css]'] = 'Theme css';
        $form['admin_journal_theme[public]'] = '1';
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $this->assertContains(
            'Theme Edit Title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDelete()
    {
        $em = $this->em;

        $adminJournalTheme = new AdminJournalTheme();
        $adminJournalTheme->setTitle('Journal Theme Delete Title - phpunit');
        $adminJournalTheme->setCss('css');
        $adminJournalTheme->setPublic(true);

        $em->persist($adminJournalTheme);
        $em->flush();

        $id = $adminJournalTheme->getId();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('ojs_admin_journal_theme'.$id);
        $client->request('DELETE', '/admin/journal-theme/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}