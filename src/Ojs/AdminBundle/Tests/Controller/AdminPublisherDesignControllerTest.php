<?php

namespace Ojs\AdminBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;
use Ojs\JournalBundle\Entity\PublisherDesign;

class AdminPublisherDesignControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/publisher-design/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/publisher-design/new');

        $this->assertStatusCode(200, $client);
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/publisher-design/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/publisher-design/1/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=ojs_adminbundle_publisher_design]')->form();

        $form['ojs_adminbundle_publisher_design[publisher]'] = '1';
        $form['ojs_adminbundle_publisher_design[title]'] = 'Publisher Design - phpunit';
        $form['ojs_adminbundle_publisher_design[editableContent]'] = 'html{}';
        $form['ojs_adminbundle_publisher_design[public]'] = '1';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Publisher Design - phpunit',
            $this->client->getResponse()->getContent()
        );
    }
    
    public function testDelete()
    {
        $em = $this->em;

        $entity = new PublisherDesign();
        $entity->setTitle('Publisher Design Delete Title');
        $entity->setContent('content');
        $entity->setEditableContent('editable');
        $entity->setPublic(false);

        $publisher = $em->getRepository('OjsJournalBundle:Publisher')->find(1);
        $entity->setPublisher($publisher);

        $em->persist($entity);
        $em->flush();

        $id = $entity->getId();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('ojs_admin_publisher_design'.$id);
        $client->request('DELETE', '/admin/publisher-design/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}