<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;

/**
 * Class JournalIndexControllerTest
 * @package Ojs\JournalBundle\Tests\Controller
 */
class JournalIndexControllerTest extends BaseTestCase
{
    public function testCreate()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', $this->router->generate('admin_journalindex_new'));
        $form = $crawler->selectButton('Create')->form();
        $form['ojs_journalbundle_journalindex[name]'] = "Demo Index";
        $form['ojs_journalbundle_journalindex[status]'] = "1";
        $form['ojs_journalbundle_journalindex[logo]'] = "image.jpg";
        $crawler = $this->client->submit($form);
        $this->assertTrue((boolean)preg_match('~(Redirecting to .*)~',$crawler->text()));
    }


    public function testStatus()
    {
        $this->logIn('admin', ['ROLE_SUPER_ADMIN']);

        $this->client->request('GET', '/admin/journalindex/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request('GET', '/admin/journalindex/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
