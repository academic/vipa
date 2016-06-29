<?php

namespace Ojs\AdminBundle\Tests\Controller;

use Ojs\AdminBundle\Entity\AdminPost;
use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class AdminPostControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/post/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/post/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=admin_post]')->form();
        $form['admin_post[translations]['.$this->locale.'][title]'] = 'Post Title - phpunit';
        $form['admin_post[translations]['.$this->locale.'][content]'] = 'Post content - phpunit';
        
        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Post Title - phpunit',
            $this->client->getResponse()->getContent()
        );

    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/post/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/post/1/edit');

        $this->assertStatusCode(200, $client);


        $form = $crawler->filter('form[name=admin_post]')->form();
        $form['admin_post[translations]['.$this->locale.'][title]'] = 'Post Edit Title - phpunit';
        $form['admin_post[translations]['.$this->locale.'][content]'] = 'Post content - phpunit';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Post Edit Title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDelete()
    {
        $em = $this->em;

        $entity = new AdminPost();
        $entity->setCurrentLocale($this->locale);
        $entity->setTitle('Post Title Delete - phpunit');
        $entity->setContent('content delete phpunit');

        $em->persist($entity);
        $em->flush();

        $id = $entity->getId();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('ojs_admin_post'.$id);
        $client->request('DELETE', '/admin/post/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);

    }
}