<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class JournalIndexControllerTest extends BaseTestCase
{
    /*
 public function testCreate()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', $this->router->generate('ojs_journal_index_new', ['journal' => 1]));
        $form = $crawler->selectButton('Create')->form();
        $form['ojs_journalbundle_journalindex[journal_index]'] = "1";
        $form['ojs_journalbundle_journalindex[link]'] = "http://google.com";
        $crawler = $this->client->submit($form);
        $this->assertTrue((boolean)preg_match('~(Redirecting to .*)~', $crawler->text()));
    }
*/

    public function testStatus()
    {
        $this->logIn('admin', ['ROLE_SUPER_ADMIN']);

        $this->client->request('GET', '/admin/journalindex');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request('GET', '/admin/journalindex/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request('GET', '/admin/journalindex/new/1');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
