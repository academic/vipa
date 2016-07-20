<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class JournalSubmissionFileControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/file/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/file/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=journal_submission_file]')->form();
        $form['journal_submission_file[file]'] = '7316/830d/766e/578f77af12f87.txt';
        $form['journal_submission_file[title]'] = 'Journal File Title - phpunit';
        $form['journal_submission_file[detail]'] = 'Journal file detail';
        $form['journal_submission_file[locale]'] = 'tr';
        $form['journal_submission_file[visible]'] = '1';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Journal File Title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/file/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $id = $this->sampleObjectLoader->loadJournalSubmissionFile();
        $crawler = $client->request('GET', '/journal/1/file/' . $id . '/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=journal_submission_file]')->form();
        $form['journal_submission_file[title]'] = 'Journal File Edit Title - phpunit';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Journal File Edit Title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }


    public function testDelete()
    {
        $id = $this->sampleObjectLoader->loadJournalSubmissionFile();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('ojs_journal_file' . $id);
        $client->request('DELETE', '/journal/1/file/' . $id . '/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}