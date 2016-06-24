<?php

namespace Ojs\AdminBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class AdminContactTypeControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/contact-type/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/contact-type/new');

        $this->assertStatusCode(200, $client);


        $form = $crawler->filter('form[name=contact_types]')->form();
        $form['contact_types[translations]['.$this->locale.'][name]'] = 'Contact Name';
        $form['contact_types[translations]['.$this->locale.'][description]'] = 'Contact Description';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $this->assertContains(
            'Contact Name',
            $this->client->getResponse()->getContent()
        );
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/contact-type/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/contact-type/2/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=contact_types]')->form();
        $form['contact_types[translations]['.$this->locale.'][name]'] = 'Contact Name Edit';
        $form['contact_types[translations]['.$this->locale.'][description]'] = 'Contact Description';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $this->assertContains(
            'Contact Name Edit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDelete()
    {
        $this->logIn();
        $client = $this->client;
        $token = $client->getContainer()->get('security.csrf.token_manager')->getToken('ojs_admin_contact_type2');
        $client->request('DELETE', '/admin/contact-type/2/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}