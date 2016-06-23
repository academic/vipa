<?php

namespace Ojs\AdminBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class AdminAnnouncementControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/announcement/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/announcement/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=admin_announcement]')->form();
        $form['admin_announcement[title]'] = 'Title';
        $form['admin_announcement[content]'] = 'http://ojs.io';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Title',
            $this->client->getResponse()->getContent()
        );
        
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/announcement/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/announcement/1/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=admin_announcement]')->form();
        $form['admin_announcement[title]'] = 'New Title';
        $form['admin_announcement[content]'] = 'http://ojs.dev';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'New Title',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDelete()
    {
        $this->logIn();
        $client = $this->client;
        $token = $client->getContainer()->get('security.csrf.token_manager')->getToken('ojs_admin_announcement2');
        $client->request('DELETE', '/admin/announcement/2/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}