<?php

namespace Vipa\AdminBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;
use Vipa\JournalBundle\Entity\ContactTypes;

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
        $em = $this->em;
        
        $contactType = new ContactTypes();
        $contactType->setCurrentLocale($this->locale);
        $contactType->setName('Delete Contact Type - phpunit');

        $em->persist($contactType);
        $em->flush();

        $id = $contactType->getId();
        
        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('vipa_admin_contact_type'.$id);
        $client->request('DELETE', '/admin/contact-type/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}