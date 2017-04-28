<?php

namespace Vipa\AdminBundle\Tests\Controller;

use Vipa\AdminBundle\Entity\AdminAnnouncement;
use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

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
        $form['admin_announcement[content]'] = 'http://vipa.io';

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
        $form['admin_announcement[content]'] = 'http://vipa.dev';

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

        $em = $this->em;

        $announcement = new AdminAnnouncement();
        $announcement->setTitle('Delete Title - phpunit');
        $announcement->setContent('Delete Content - phpunit');

        $em->persist($announcement);
        $em->flush();

        $id = $announcement->getId();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('vipa_admin_announcement'.$id);
        $client->request('DELETE', '/admin/announcement/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}