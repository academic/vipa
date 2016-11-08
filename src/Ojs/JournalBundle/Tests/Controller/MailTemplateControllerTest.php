<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class MailTemplateControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/mail-template/');

        $this->assertStatusCode(200, $client);
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $entity = $this->sampleObjectLoader->fetchMailTemplate();
        $client->request('GET', '/journal/1/mail-template/'.$entity->getId().'/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $entity = $this->sampleObjectLoader->fetchMailTemplate();
        $crawler = $client->request('GET', '/journal/1/mail-template/'.$entity->getId().'/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=mail_template]')->form();
        $form['mail_template[useJournalDefault]'] = '1';
        $form['mail_template[subject]'] = 'Mail Template Edit - phpunit';
        $form['mail_template[template]'] = 'Mail Template phpunit';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $crawler = $client->getCrawler();
        $this->assertEquals(1, $crawler->filter('.alert-success')->count());
    }
}