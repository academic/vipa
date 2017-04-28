<?php

namespace Vipa\AdminBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;
use Vipa\JournalBundle\Entity\Institution;

class AdminInstitutionControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/institution/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/institution/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=institution]')->form();
        $form['institution[name]'] = 'Institution Name';
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $this->assertContains(
            'Institution Name',
            $client->getResponse()->getContent()
        );
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/institution/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/institution/1/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=institution]')->form();
        $form['institution[name]'] = 'Institution Name Edit';
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $this->assertContains(
            'Institution Name Edit',
            $client->getResponse()->getContent()
        );
    }

    public function testDelete()
    {
        $em = $this->em;

        $institution = new Institution();
        $institution->setCurrentLocale($this->locale);
        $institution->setEmail('institution@vipa.io');
        $institution->setName('Delete Institution Name - phpunit');
        $institution->setUrl('http://vipa.io');
        $institution->setAbout('About');
        $institution->setPhone('0123456789');
        $institution->setAddress('address');

        $publisherType = $em->getRepository('VipaJournalBundle:PublisherTypes')->find(1);
        $institution->setInstitutionType($publisherType);

        $em->persist($institution);
        $em->flush();

        $id = $institution->getId();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('vipa_admin_institution'.$id);
        $client->request('DELETE', '/admin/institution/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}