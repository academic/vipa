<?php

namespace Vipa\JournalBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class JournalPostControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/post');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/post/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=journal_post]')->form();
        $form['journal_post[translations][en][title]'] = 'Post Title - phpunit';
        $form['journal_post[translations][en][content]'] = 'content post phpunit en';
        $form['journal_post[translations][tr][title]'] = 'Post Title - phpunit';
        $form['journal_post[translations][tr][content]'] = 'content post phpunit tr';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Post Title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/post/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $id = $this->sampleObjectLoader->loadJournalPost();
        $crawler = $client->request('GET', '/journal/1/post/' . $id . '/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=journal_post]')->form();
        $form['journal_post[translations][tr][title]'] = 'Post Edit Title - phpunit';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Post Edit Title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDelete()
    {
        $id = $this->sampleObjectLoader->loadJournalPost();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('vipa_journal_post' . $id);
        $client->request('DELETE', '/journal/1/post/' . $id . '/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}