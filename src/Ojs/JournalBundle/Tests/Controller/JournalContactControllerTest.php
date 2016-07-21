<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class JournalContactControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/contact/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/contact/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=journal_contact]')->form();
        $form['journal_contact[contactType]'] = '1';
        $form['journal_contact[fullName]'] = 'Contact Name - phpunit';
        $form['journal_contact[address]'] = 'contact address phpunit';
        $form['journal_contact[phone]'] = '05005005050';
        $form['journal_contact[email]'] = 'contactPhpunit@ojs.io';
        $form['journal_contact[institution]'] = '1';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Contact Name - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/contact/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;

        $entityId = $this->sampleObjectLoader->loadContact();
        $crawler = $client->request('GET', '/journal/1/contact/' . $entityId . '/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=journal_contact]')->form();
        $form['journal_contact[contactType]'] = '1';
        $form['journal_contact[fullName]'] = 'Contact Edit Name - phpunit';
        $form['journal_contact[address]'] = 'contact Edit address phpunit';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Contact Edit Name - phpunit',
            $this->client->getResponse()->getContent()
        );

    }

    public function testDelete()
    {
        $id = $this->sampleObjectLoader->loadContact();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('ojs_journal_journal_contact' . $id);
        $client->request('DELETE', '/journal/1/contact/' . $id . '/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}