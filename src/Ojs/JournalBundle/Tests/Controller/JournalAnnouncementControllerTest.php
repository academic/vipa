<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;
use Ojs\JournalBundle\Entity\JournalAnnouncement;

class JournalAnnouncementControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/announcement');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/announcement/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=journal_announcement]')->form();
        $form['journal_announcement[translations][en][title]'] = 'Announcement title - phpunit';
        $form['journal_announcement[translations][tr][title]'] = 'Announcement title - phpunit';
        $form['journal_announcement[translations][en][content]'] = 'Announcement content en';
        $form['journal_announcement[translations][tr][content]'] = 'Announcement content tr';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Announcement title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/announcement/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/announcement/1/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=journal_announcement]')->form();
        $form['journal_announcement[translations][en][title]'] = 'Announcement Edit title - phpunit';
        $form['journal_announcement[translations][tr][title]'] = 'Announcement Edit title - phpunit';
        $form['journal_announcement[translations][en][content]'] = 'Announcement edit content en';
        $form['journal_announcement[translations][tr][content]'] = 'Announcement edit content tr';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Announcement Edit title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDelete()
    {

        $em = $this->em;

        $entity = new JournalAnnouncement();
        $entity->setCurrentLocale('en');
        $entity->setTitle('Announcement delete title - phpunit');
        $entity->setContent('http://ojs.dev');

        $journal = $em->getRepository('OjsJournalBundle:Journal')->find('1');
        $entity->setJournal($journal);


        $em->persist($entity);
        $em->flush();

        $id = $entity->getId();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('ojs_journal_announcement'.$id);
        $client->request('DELETE', '/journal/1/announcement/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }


}