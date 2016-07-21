<?php

namespace Ojs\AdminBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;
use Ojs\JournalBundle\Entity\ArticleTypes;

class AdminArticleTypeControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/article-type/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/article-type/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=article_types]')->form();
        $form['article_types[translations]['.$this->locale.'][name]'] = 'Article Name';
        $form['article_types[translations]['.$this->locale.'][description]'] = 'Article Description';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $this->assertContains(
            'Article Name',
            $this->client->getResponse()->getContent()
        );
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/article-type/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/article-type/2/edit');

        $this->assertStatusCode(200, $client);
        $form = $crawler->filter('form[name=article_types]')->form();
        $form['article_types[translations]['.$this->locale.'][name]'] = 'Article Name Edit';
        $form['article_types[translations]['.$this->locale.'][description]'] = 'Article Description';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Article Name Edit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDelete()
    {
        $em = $this->em;

        $articleType = new ArticleTypes();
        $articleType->setCurrentLocale($this->locale);
        $articleType->setName('Delete Article Type Name - phpunit');

        $em->persist($articleType);
        $em->flush();

        $id = $articleType->getId();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('ojs_admin_article_type'.$id);
        $client->request('DELETE', '/admin/article-type/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}
