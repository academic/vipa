<?php

namespace Ojs\SiteBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class ApplicationControllerTest extends BaseTestCase
{
    const APPLY_JOURNAL = 'ojs_apply_journal';
    const APPLY_PUBLISHER = 'ojs_apply_publisher';
    const APPLY_INSTITUTE_SUCCESS = 'ojs_apply_institute_success';
    const APPLY_JOURNAL_SUCCESS = 'ojs_apply_journal_success';

    public function testJournal()
    {
        $url                = $this->router->generate(self::APPLY_JOURNAL);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPublisher()
    {
        $url                = $this->router->generate(self::APPLY_PUBLISHER);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testInstituteSuccess()
    {
        $url                = $this->router->generate(self::APPLY_INSTITUTE_SUCCESS);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Success', $response->getContent());
    }

    public function testJournalSuccess()
    {
        $url                = $this->router->generate(self::APPLY_JOURNAL_SUCCESS);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Success', $response->getContent());
    }
}