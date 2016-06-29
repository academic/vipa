<?php

namespace Ojs\AdminBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;
use Ojs\JournalBundle\Entity\Period;

class AdminPeriodControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
            $client->request('GET', '/admin/period/');

        $this->assertStatusCode(200, $client);
    }

    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/period/new');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=period]')->form();
        $form['period[translations]['.$this->locale.'][period]'] = 'Period - phpunit';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Period - phpunit',
            $this->client->getResponse()->getContent()
        );

    }

    public function testShow()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/period/1/show');

        $this->assertStatusCode(200, $client);
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/period/1/edit');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=period]')->form();
        $form['period[translations]['.$this->locale.'][period]'] = 'Period Edit - phpunit';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        $this->assertContains(
            'Period Edit - phpunit',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDelete()
    {
        $em = $this->em;

        $entity = new Period();
        $entity->setCurrentLocale($this->locale);
        $entity->setPeriod('Period');

        $em->persist($entity);
        $em->flush();

        $id = $entity->getId();

        $this->logIn();
        $client = $this->client;
        $token = $this->generateToken('ojs_admin_period'.$id);
        $client->request('DELETE', '/admin/period/'.$id.'/delete', array('_token' => $token));

        $this->assertStatusCode(302, $client);
    }
}