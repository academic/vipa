<?php

namespace Vipa\SiteBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class JournalCmsControllerTest extends BaseTestCase
{
    public function testAnnouncementIndex()
    {
        $client = $this->client;
        $client->request('GET', '/intro/announcements');
        $this->assertStatusCode(200, $client);
    }

    public function testJournalPageDetail()
    {
        $client = $this->client;
        $client->request('GET', '/intro/page/title-page');
        $this->assertStatusCode(200, $client);
    }

    public function testJournalPostDetail()
    {
        $client = $this->client;
        $client->request('GET', '/intro/post/title-post');
        $this->assertStatusCode(200, $client);
    }
}
