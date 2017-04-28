<?php

namespace Vipa\JournalBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class JournalIndexControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/index/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/index/new/');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=journal_index]')->form();
        $form['journal_index[index]'] = '3';
        $form['journal_index[link]'] = 'http://phpunit-vipa.io';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'http://phpunit-vipa.io',
            $this->client->getResponse()->getContent()
        );
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/index/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $id = $this->sampleObjectLoader->loadJournalIndex();
        $crawler = $client->request('GET', '/journal/1/index/' . $id . '/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=journal_index]')->form();
        $form['journal_index[link]'] = 'http://phpunit-edit-vipa.io';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'http://phpunit-edit-vipa.io',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDelete()
    {
        $id = $this->sampleObjectLoader->loadJournalIndex();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('vipa_journal_index' . $id);
        $client->request('DELETE', '/journal/1/index/' . $id . '/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}