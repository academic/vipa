<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;
use Ojs\JournalBundle\Entity\Block;

class BlockControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/block');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/block/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=block]')->form();
        $form['block[translations][en][title]'] = 'Block title - phpunit';
        $form['block[translations][en][content]'] = 'content en - phpunit';
        $form['block[translations][tr][title]'] = 'Block title - phpunit';
        $form['block[translations][tr][content]'] = 'content tr - phpunit';
        $form['block[blockOrder]'] = '1';
        $form['block[color]'] = 'default';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Block title - phpunit',
            $this->client->getResponse()->getContent()
        );

    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/block/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/block/1/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=block]')->form();
        $form['block[translations][en][title]'] = 'Block Edit title - phpunit';
        $form['block[translations][en][content]'] = 'content en - phpunit';
        $form['block[translations][tr][title]'] = 'Block Edit title - phpunit';
        $form['block[translations][tr][content]'] = 'content tr - phpunit';
        $form['block[blockOrder]'] = '1';
        $form['block[color]'] = 'default';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Block Edit title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDelete()
    {

        $em = $this->em;

        $entity = new Block();
        $entity->setCurrentLocale('en');
        $entity->setTitle('Block');
        $entity->setBlockOrder(1);
        $entity->setColor('success');

        $journal = $em->getRepository('OjsJournalBundle:Journal')->find('1');
        $entity->setJournal($journal);

        $em->persist($entity);
        $em->flush();

        $id = $entity->getId();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('ojs_journal_block'.$id);
        $client->request('DELETE', '/journal/1/block/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
    
    
}