<?php

namespace Ojs\SiteBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class JournalCmsControllerTest extends BaseTestCase
{
    public function testAnnouncementIndex()
    {
        $client = static::makeClient(array(), array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET', '/intro/announcements');
        $this->assertStatusCode(200, $client);
    }

    public function testJournalPageDetail()
    {
        $client = static::makeClient(array(), array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET', '/intro/page/title-page');
        $this->assertStatusCode(200, $client);
    }

    public function testJournalPostDetail()
    {
        $client = static::makeClient(array(), array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET', '/intro/post/title-post');
        $this->assertStatusCode(200, $client);
    }
}
