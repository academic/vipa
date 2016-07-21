<?php

namespace Ojs\AdminBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;
use Ojs\JournalBundle\Entity\JournalApplicationFile;

class AdminJournalApplicationFileControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/journal-application-file/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/journal-application-file/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=journal_application_file]')->form();
        $form['journal_application_file[title]'] = 'Journal File Title - phpunit';
        $form['journal_application_file[file]'] = '81a2/8eca/ea43/5771db9974ddc.';
        $form['journal_application_file[detail]'] = 'Detail - phpunit';
        $form['journal_application_file[locale]'] = 'tr';
        $form['journal_application_file[visible]'] = '1';
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $this->assertContains(
            'Journal File Title - phpunit',
            $client->getResponse()->getContent()
        );

    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/journal-application-file/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/journal-application-file/1/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=journal_application_file]')->form();
        $form['journal_application_file[title]'] = 'Journal File Edit Title - phpunit';
        $form['journal_application_file[detail]'] = 'Detail - phpunit';
        $form['journal_application_file[locale]'] = 'tr';
        $form['journal_application_file[visible]'] = '1';
        $form['journal_application_file[required]'] = '1';
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $this->assertContains(
            'Journal File Edit Title - phpunit',
            $client->getResponse()->getContent()
        );
    }


}
