<?php

namespace Vipa\JournalBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;
use Vipa\JournalBundle\Entity\Citation;

class CitationControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/article/1/citation/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/article/1/citation/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=citation]')->form();
        $form['citation[raw]'] = 'Citation title - phpunit';
        $form['citation[type]'] = '0';
        $form['citation[orderNum]'] = '2';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Citation title - phpunit',
            $this->client->getResponse()->getContent()
        );

    }
    
    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/article/1/citation/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/article/1/citation/1/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=citation]')->form();
        $form['citation[raw]'] = 'Citation Edit title - phpunit';
        $form['citation[type]'] = '0';
        $form['citation[orderNum]'] = '2';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Citation Edit title - phpunit',
            $this->client->getResponse()->getContent()
        );

    }

    public function testDelete()
    {

        $em = $this->em;

        $entity = new Citation();
        $entity->setCurrentLocale('en');
        $entity->setRaw('Citation delete title');
        $entity->setOrderNum(0);
        
        $article = $em->getRepository('VipaJournalBundle:Article')->find('1');
        $entity->addArticle($article);

        $em->persist($entity);
        $em->flush();

        $id = $entity->getId();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('vipa_journal_citation'.$id);
        $client->request('DELETE', '/journal/1/article/1/citation/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
    
}