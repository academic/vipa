<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class JournalsIndexControllerTest extends BaseTestCase
{
    /*
 public function testCreate()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', $this->router->generate('ojs_journal_index_new', ['journal' => 1]));
        $form = $crawler->selectButton('Create')->form();
        $form['ojs_journalbundle_journalsindex[journal_index]'] = "1";
        $form['ojs_journalbundle_journalsindex[link]'] = "http://google.com";
        $crawler = $this->client->submit($form);
        $this->assertTrue((boolean)preg_match('~(Redirecting to .*)~', $crawler->text()));
    }
*/

    public function testStatus()
    {
        $this->logIn('admin', ['ROLE_SUPER_ADMIN']);

        $this->client->request('GET', '/admin/journalsindex');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request('GET', '/admin/journalsindex/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request('GET', '/admin/journalsindex/new/1');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
