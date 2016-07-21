<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class SectionControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/section/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/section/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=section]')->form();
        $form['section[translations][en][title]'] = 'Section Title - phpunit';
        $form['section[translations][tr][title]'] = 'Section Title - phpunit';
        $form['section[allowIndex]'] = '1';
        $form['section[sectionOrder]'] = '1';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Section Title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/section/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $id = $this->sampleObjectLoader->loadSection();
        $crawler = $client->request('GET', '/journal/1/section/' . $id . '/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=section]')->form();
        $form['section[translations][en][title]'] = 'Section Edit Title - phpunit';
        $form['section[translations][tr][title]'] = 'Section Edit Title - phpunit';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Section Edit Title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDelete()
    {
        $id = $this->sampleObjectLoader->loadSection();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('ojs_journal_section' . $id);
        $client->request('DELETE', '/journal/1/section/' . $id . '/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}