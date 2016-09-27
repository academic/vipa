<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;
use Ojs\JournalBundle\Entity\Article;

class ArticleControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/article');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/article/new');

        $this->assertStatusCode(200, $client);

    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/article/1');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/article/1/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=article]')->form();
        $form['article[translations][en][title]'] = 'Article Edit Title - phpunit';
        $form['article[translations][en][abstract]'] = 'abstract en - phpunit';
        $form['article[translations][tr][title]'] = 'Article Edit Title - phpunit';
        $form['article[translations][tr][abstract]'] = 'abstract tr - phpunit';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
        'Article Edit Title - phpunit',
        $this->client->getResponse()->getContent()
    );

    }

    public function testDelete()
    {

        $em = $this->em;


        $journal = $em->getRepository('OjsJournalBundle:Journal')->find('1');

        $entity = new Article();
        $entity->setCurrentLocale($this->locale);
        $entity->setAnonymous(false);
        $entity->setTitle('Article Title Delete');
        $entity->setStatus(1);
        $entity->setJournal($journal);

        $em->persist($entity);
        $em->flush();

        $id = $entity->getId();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('ojs_journal_article'.$id);
        $client->request('DELETE', '/journal/1/article/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }


}

