<?php

namespace Ojs\AdminBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class AdminContactControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/contact/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/contact/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=contact]')->form();

        $form['contact[title]'] = '';
        $form['contact[fullName]'] = 'Contact Name Phpunit';
        $form['contact[address]'] = 'Address';
        $form['contact[phone]'] = '05005005050';
        $form['contact[email]'] = 'contact@ojs.io';
        $form['contact[tags]'] = ['phpunit'];
        $form['contact[contactType]'] = 1;
        $form['contact[journal]'] = 1;
        $form['contact[institution]'] = 1;
        $form['contact[country]'] = 225;
        $form['contact[_token]'] = $this->generateToken('ojs_admin_contact');

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Contact Name Phpunit',
            $this->client->getResponse()->getContent()
        );

    }
    
    /*
    public function testCreate()
    {
        $form['contact[title]'] = '';
        $form['contact[fullName]'] = 'Contact Name Phpunit';
        $form['contact[address]'] = 'Address';
        $form['contact[phone]'] = '05005005050';
        $form['contact[email]'] = 'contact@ojs.io';
        $form['contact[tags]'] = ['phpunit'];
        $form['contact[contactType]'] = 1;
        $form['contact[journal]'] = 1;
        $form['contact[institution]'] = 1;
        $form['contact[country]'] = 225;
        $form['contact[_token]'] = $this->generateToken('ojs_admin_contact');
        
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('POST', '/admin/contact/create', $form);
        var_dump($client->getResponse()->getContent());exit;
        $this->assertStatusCode(302, $client);



    }
    */
    
    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/contact/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/contact/1/edit');

        $this->assertStatusCode(200, $client);
    }
}