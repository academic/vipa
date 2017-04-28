<?php

namespace Vipa\JournalBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;
use Vipa\JournalBundle\Entity\ArticleFile;

class ArticleFileControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/article/1/file');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/article/1/file/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=article_file]')->form();
        $form['article_file[file]'] = 'file';
        $form['article_file[type]'] = '0';
        $form['article_file[langCode]'] = '1';
        $form['article_file[title]'] = 'Article File - phpunit';
        $form['article_file[description]'] = 'description - phpunit';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Article File - phpunit',
            $this->client->getResponse()->getContent()
        );

    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/journal/1/article/1/file/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/journal/1/article/1/file/1/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=article_file]')->form();
        $form['article_file[title]'] = 'Article Edit File - phpunit';
        $form['article_file[description]'] = 'description - phpunit';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Article Edit File - phpunit',
            $this->client->getResponse()->getContent()
        );

    }
    
    public function testDelete()
    {
        $em = $this->em;
        $article = $em->getRepository('VipaJournalBundle:Article')->find('1');

        $entity = new ArticleFile();
        $entity->setTitle('Article File Delete');
        $entity->setArticle($article);
        $entity->setDescription('Description - phpunit');

        $em->persist($entity);
        $em->flush();

        $id = $entity->getId();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('vipa_journal_article_file'.$id);
        $client->request('DELETE', '/journal/1/article/1/file/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
    
    


}

