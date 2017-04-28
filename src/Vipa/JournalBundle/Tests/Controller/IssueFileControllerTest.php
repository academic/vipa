<?php

namespace Vipa\JournalBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;
use Vipa\JournalBundle\Entity\IssueFile;

class IssueFileControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/issue/1/file/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/issue/1/file/new');

        $this->assertStatusCode(200, $client);
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/issue/1/file/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/issue/1/file/1/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=issue_file]')->form();
        $form['issue_file[translations][en][title]'] = 'Issue file Edit title - phpunit';
        $form['issue_file[translations][en][description]'] = 'description en';
        $form['issue_file[translations][tr][title]'] = 'Issue file Edit title - phpunit';
        $form['issue_file[translations][tr][description]'] = 'description tr';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Issue file Edit title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDelete()
    {
        $em = $this->em;

        $entity = new IssueFile();
        $entity->setCurrentLocale('en');
        $entity->setTitle('Demo File');
        $entity->setDescription('A file');
        $entity->setFile('issue.txt');
        $entity->setLangCode('en');
        $entity->setType(0);
        $entity->setVersion(0);

        $issue = $em->getRepository('VipaJournalBundle:Issue')->find('1');
        $entity->setIssue($issue);

        $em->persist($entity);
        $em->flush();

        $id = $entity->getId();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('vipa_journal_issue_file'.$id);
        $client->request('DELETE', '/journal/1/issue/1/file/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}
