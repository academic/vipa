<?php

namespace Ojs\AdminBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class AdminUserControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/user/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/user/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=user]')->form();
        $form['user[username]'] = 'UserPhpunit';
        $form['user[password]'] = 'phpunitPassword';
        $form['user[email]'] = 'email@ojs.io';
        $form['user[about]'] = 'User About - phpunit';
        $form['user[title]'] = '1';
        $form['user[firstName]'] = 'First Name - phpunit';
        $form['user[lastName]'] = 'Last Name - phpunit';
        $form['user[enabled]'] = '1';
        $form['user[subjects]'] = ['2'];
        $form['user[avatar]'] = '';
        $form['user[country]'] = '225';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'First Name - phpunit',
            $this->client->getResponse()->getContent()
        );

    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/user/1/edit');

        $this->assertStatusCode(200, $client);
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/user/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testChangePassword()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/user/1/password');

        $this->assertStatusCode(200, $client);
    }
}
