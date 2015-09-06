<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

/**
 * Class IndexControllerTest
 * @package Ojs\JournalBundle\Tests\Controller
 */
class IndexControllerTest extends BaseTestCase
{
    public function testCreate()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', $this->router->generate('ojs_admin_index_new'));
        $form = $crawler->selectButton('Create')->form();
        $form['ojs_journalbundle_index[name]'] = "Demo Index";
        $form['ojs_journalbundle_index[status]'] = "1";
        $form['ojs_journalbundle_index[logo]'] = "image.jpg";
        $crawler = $this->client->submit($form);
        $this->assertTrue((boolean)preg_match('~(Redirecting to .*)~', $crawler->text()));
    }

    public function testStatus()
    {
        $this->logIn('admin', ['ROLE_SUPER_ADMIN']);

        $this->client->request('GET', '/admin/index/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request('GET', '/admin/index/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
