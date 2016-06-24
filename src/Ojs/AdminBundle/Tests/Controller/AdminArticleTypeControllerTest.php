<?php

namespace Ojs\AdminBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

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
        $this->logIn();
        $client = $this->client;
        $token = $client->getContainer()->get('security.csrf.token_manager')->getToken('ojs_admin_article_type2');
        $client->request('DELETE', '/admin/article-type/2/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}
