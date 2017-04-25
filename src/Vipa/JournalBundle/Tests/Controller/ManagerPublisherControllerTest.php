<?php

namespace Vipa\JournalBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class ManagerPublisherControllerTest extends BaseTestCase
{
    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/publisher/1/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=publisher]')->form();
        $form['publisher[translations][tr][name]'] = 'Publisher Edit - phpunit';
        $form['publisher[translations][en][name]'] = 'Publisher Edit - phpunit';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $crawler = $client->getCrawler();
        $this->assertEquals(1, $crawler->filter('.alert-success')->count());


    }
}