<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class JournalPageControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/page');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/page/new');

        $this->assertStatusCode(200, $client);
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/page/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $id = $this->sampleObjectLoader->loadJournalPage();
        $crawler = $client->request('GET', '/journal/1/page/' . $id . '/edit');

        $this->assertStatusCode(200, $client);
        $form = $crawler->filter('form[name=journal_page]')->form();
        $form['journal_page[translations][en][title]'] = 'Page Edit Title - phpunit';
        $form['journal_page[translations][en][body]'] = 'content page phpunit en';
        $form['journal_page[translations][tr][title]'] = 'Page Edit Title - phpunit';
        $form['journal_page[translations][tr][body]'] = 'content page phpunit tr';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Page Edit Title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDelete()
    {
        $id = $this->sampleObjectLoader->loadJournalPage();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('ojs_journal_page' . $id);
        $client->request('DELETE', '/journal/1/page/' . $id . '/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}