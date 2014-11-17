<?php

namespace Ojs\AnalyticsBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->client->request('GET', $this->router->generate('analytics_homepage'));
        /** @var Response $response */
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testJournalSummary()
    {
        $this->client->request('GET', $this->router->generate('analytics_summary_journals_all'));
        /** @var Response $response */
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

}
