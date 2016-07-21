<?php

namespace Ojs\AdminBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;
use Ojs\JournalBundle\Entity\PersonTitle;

class AdminPersonTitleControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/person-title/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/person-title/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=person_title]')->form();
        $form['person_title[translations]['.$this->locale.'][title]'] = 'Person Title - phpunit';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Person Title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/person-title/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/person-title/1/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=person_title]')->form();
        $form['person_title[translations]['.$this->locale.'][title]'] = 'Person Edit Title - phpunit';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Person Edit Title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDelete()
    {
        $em = $this->em;

        $entity = new PersonTitle();
        $entity->setCurrentLocale($this->locale);
        $entity->setTitle('Title');

        $em->persist($entity);
        $em->flush();

        $id = $entity->getId();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('ojs_admin_person_title'.$id);
        $client->request('DELETE', '/admin/person-title/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}