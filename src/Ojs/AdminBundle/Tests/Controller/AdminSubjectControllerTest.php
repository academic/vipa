<?php

namespace Ojs\AdminBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;
use Ojs\JournalBundle\Entity\Subject;

class AdminSubjectControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/subject/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/subject/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=subject]')->form();
        $form['subject[translations]['.$this->locale.'][subject]'] = 'Subject - phpunit';
        $form['subject[translations]['.$this->locale.'][description]'] = 'content - phpunit';
        $form['subject[parent]'] = '1';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Subject - phpunit',
            $this->client->getResponse()->getContent()
        );


    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/subject/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/subject/1/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=subject]')->form();
        $form['subject[translations]['.$this->locale.'][subject]'] = 'Subject Edit - phpunit';
        $form['subject[translations]['.$this->locale.'][description]'] = 'content Edit - phpunit';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Subject Edit - phpunit',
            $this->client->getResponse()->getContent()
        );
    }



    public function testDelete()
    {
        $em = $this->em;
        
        $entity = new Subject();
        $entity->setCurrentLocale($this->locale);
        $entity->setSubject('Subject - phpunit');
        $entity->setTags('tags, phpunit');
        
        $em->persist($entity);
        $em->flush();

        $id = $entity->getId();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('ojs_admin_subject'.$id);
        $client->request('DELETE', '/admin/subject/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);

    }
}