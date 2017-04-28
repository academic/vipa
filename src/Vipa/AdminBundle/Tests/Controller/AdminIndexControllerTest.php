<?php

namespace Vipa\AdminBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;
use Vipa\JournalBundle\Entity\Index;

class AdminIndexControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/index/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/index/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=index]')->form();
        $form['index[name]'] = 'Index Name';
        $form['index[logo]'] = 'f6e2/885d/192d/5771348a32d76.png';
        $form['index[status]']->tick();
        $client->submit($form);
        
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $this->assertContains(
            'Index Name',
            $this->client->getResponse()->getContent()
        );
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/index/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/index/2/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=index]')->form();
        $form['index[name]'] = 'Index Name Edit';
        $form['index[status]']->tick();
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $this->assertContains(
            'Index Name Edit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDelete()
    {
        $em = $this->em;

        $index = new Index();
        $index->setName('Delete Index - phpunit');
        $index->setLogo('logo.png');
        $index->setStatus(true);

        $em->persist($index);
        $em->flush();

        $id = $index->getId();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('vipa_admin_index'.$id);
        $client->request('DELETE', '/admin/index/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}

