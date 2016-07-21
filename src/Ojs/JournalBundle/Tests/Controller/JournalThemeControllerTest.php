<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class JournalThemeControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/theme/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/theme/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=journal_theme]')->form();
        $form['journal_theme[title]'] = 'Journal Theme Title - phpunit';
        $form['journal_theme[css]'] = 'css';
        $form['journal_theme[public]'] = '1';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Journal Theme Title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/theme/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $id = $this->sampleObjectLoader->loadJournalTheme();
        $crawler = $client->request('GET', '/journal/1/theme/' . $id . '/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=journal_theme]')->form();
        $form['journal_theme[title]'] = 'Journal Theme Edit Title - phpunit';
        $form['journal_theme[css]'] = 'css';
        $form['journal_theme[public]'] = '1';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Journal Theme Edit Title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testGlobalThemes()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/theme/global-themes');

        $this->assertStatusCode(200, $client);
    }

    public function testCloneGlobalTheme()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/theme/2/clone?type=global');

        $this->assertStatusCode(302, $client);
        $client->followRedirect();
        $crawler = $client->getCrawler();
        $this->assertEquals(1, $crawler->filter('.alert-success')->count());

    }


    public function testDelete()
    {
        $id = $this->sampleObjectLoader->loadJournalTheme();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('ojs_journal_theme' . $id);
        $client->request('DELETE', '/journal/1/theme/' . $id . '/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}