<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;

class JournalControllerTest extends BaseTestCase
{


    public function testStatus()
    {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $this->client->request('GET', '/admin/journal/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request('GET', '/admin/journal/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testCreate()
    {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));
        $crawler = $this->client->request('GET', $this->router->generate('journal_new'));
        $form = $crawler->selectButton('Create')->form();
        $form['ojs_journalbundle_journal[title]'] = "Test Content";
        $form['ojs_journalbundle_journal[titleAbbr]'] = 'Content';
        $form['ojs_journalbundle_journal[titleTransliterated]'] = 'Content';
        $form['ojs_journalbundle_journal[languages]'] = [1,2];
        $form['ojs_journalbundle_journal[subtitle]'] = 'Content';
        $form['ojs_journalbundle_journal[subdomain]'] = 'Content';
        $form['ojs_journalbundle_journal[domain]'] = 'Content';
        $form['ojs_journalbundle_journal[issn]'] = 'Content';
        $form['ojs_journalbundle_journal[eissn]'] = 'Content';
        $form['ojs_journalbundle_journal[firstPublishDate][date]'] = '2014-12-01';
        $form['ojs_journalbundle_journal[firstPublishDate][time]'] = '01:01:43';
        $form['ojs_journalbundle_journal[period]'] = 'Content';
        $form['ojs_journalbundle_journal[url]'] = 'Content';
        $form['ojs_journalbundle_journal[countryId]'] = 1;
        $form['ojs_journalbundle_journal[published]'] = 1;
        $form['ojs_journalbundle_journal[status]'] = 1;
        $form['ojs_journalbundle_journal[image]'] = 'Content';
        $form['ojs_journalbundle_journal[scope]'] = 'Content';
        $form['ojs_journalbundle_journal[mission]'] = 'Content';
        $form['ojs_journalbundle_journal[slug]'] = 'Content';
        $form['ojs_journalbundle_journal[theme]'] = 3;

        $crawler = $this->client->submit($form);

        $this->assertTrue((boolean)preg_match('~(Redirecting to .*)~',$crawler->text()));
    }

}
