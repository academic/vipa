<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;
use Ojs\JournalBundle\Entity\Issue;

class IssueControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/issue');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/issue/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=issue]')->form();
        $form['issue[translations][en][title]'] = 'Issue title - phpunit';
        $form['issue[translations][en][description]'] = 'Description en';
        $form['issue[translations][tr][title]'] = 'Issue title - phpunit';
        $form['issue[translations][tr][description]'] = 'Description tr';
        $form['issue[volume]'] = '1';
        $form['issue[number]'] = '2';
        $form['issue[display_mode]'] = '0';
        $form['issue[visibility]'] = '0';
        $form['issue[year]'] = '2016';
        $form['issue[datePublished]'] = '15-07-2016';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Issue title - phpunit',
            $this->client->getResponse()->getContent()
        );

    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/issue/1');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/issue/1/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=issue]')->form();
        $form['issue[translations][en][title]'] = 'Issue Edit title - phpunit';
        $form['issue[translations][en][description]'] = 'Description en';
        $form['issue[translations][tr][title]'] = 'Issue Edit title - phpunit';
        $form['issue[translations][tr][description]'] = 'Description tr';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Issue Edit title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testArrange()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/issue/1/arrange');

        $this->assertStatusCode(200, $client);
    }

    public function testView()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/issue/1/article');

        $this->assertStatusCode(200, $client);
    }

    public function testMakeLastIssue()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/issue/1/make-last');

        $this->assertStatusCode(302, $client);
    }

    public function testDelete()
    {
        $em = $this->em;

        $entity = new Issue();
        $entity->setCurrentLocale('en');
        $entity->setTitle('Issue title delete - phpunit');
        $entity->setDescription('description delete issue');
        $entity->setNumber(1);
        $entity->setVolume(1);
        $entity->setYear(2015);
        $entity->setSpecial(1);
        $entity->setDatePublished(new \DateTime('now'));


        $journal = $em->getRepository('OjsJournalBundle:Journal')->find('1');
        $entity->setJournal($journal);

        $em->persist($entity);
        $em->flush();

        $id = $entity->getId();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('ojs_journal_issue'.$id);
        $client->request('DELETE', '/journal/1/issue/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}
