<?php

namespace Vipa\JournalBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;
use Vipa\JournalBundle\Entity\ArticleAuthor;
use Vipa\JournalBundle\Entity\Author;

class ArticleAuthorControllerTest extends BaseTestCase
{

    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET','/journal/1/article/1/author');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/article/1/author/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=article_author]')->form();
        $form['article_author[author][translations][en][biography]'] = 'Biography En - Phpunit';
        $form['article_author[author][translations][tr][biography]'] = 'Biography Tr - Phpunit';
        $form['article_author[author][title]'] = '1';
        $form['article_author[author][firstName]'] = 'FirstName phpunit';
        $form['article_author[author][lastName]'] = 'LastName phpunit';
        $form['article_author[author][phone]'] = '05005005050';
        $form['article_author[author][email]'] = 'author@vipa.io';
        $form['article_author[author][institution]'] = '1';
        $form['article_author[authorOrder]'] = '1';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'FirstName phpunit',
            $this->client->getResponse()->getContent()
        );


    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/article/1/author/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/article/1/author/1/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=article_author]')->form();
        $form['article_author[author][firstName]'] = 'FirstName Edit phpunit';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'FirstName Edit phpunit',
            $this->client->getResponse()->getContent()
        );

    }

    public function testDelete()
    {
        $em = $this->em;

        $author = new Author();
        $author->setFirstName('firstName delete');
        $author->setLastName('lastName delete');

        $em->persist($author);
        $em->flush();
        $article = $em->getRepository('VipaJournalBundle:Article')->find('1');

        $entity = new ArticleAuthor();
        $entity->setAuthor($author);
        $entity->setArticle($article);
        $entity->setAuthorOrder('1');

        $em->persist($entity);
        $em->flush();

        $id = $entity->getId();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('vipa_journal_article_author'.$id);
        $client->request('DELETE', '/journal/1/article/1/author/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
    
    
}

