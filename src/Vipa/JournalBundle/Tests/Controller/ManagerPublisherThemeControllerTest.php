<?php

namespace Vipa\JournalBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class ManagerPublisherThemeControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/publisher/1/theme/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/publisher/1/theme/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=publisher_theme]')->form();
        $form['publisher_theme[title]'] = 'Publisher Theme Title - phpunit';
        $form['publisher_theme[public]'] = '1';
        $form['publisher_theme[css]'] = 'css';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Publisher Theme Title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/publisher/1/theme/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $id = $this->sampleObjectLoader->loadPublisherTheme();
        $crawler = $client->request('GET', '/publisher/1/theme/' . $id . '/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=publisher_theme]')->form();
        $form['publisher_theme[title]'] = 'Publisher Theme Edit Title - phpunit';
        $form['publisher_theme[public]'] = '1';
        $form['publisher_theme[css]'] = 'css';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Publisher Theme Edit Title - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDelete()
    {
        $id = $this->sampleObjectLoader->loadPublisherTheme();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('vipa_publisher_manager_theme' . $id);
        $client->request('DELETE', '/publisher/1/theme/' . $id . '/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}