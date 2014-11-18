<?php
/**
 * User: aybarscengaver
 * Date: 16.11.14
 * Time: 20:59
 * URI: www.emre.xyz
 * Devs: [
 * 'Aybars Cengaver'=>'aybarscengaver@yahoo.com',
 *   ]
 */

namespace Ojs\AnalyticsBundle\Tests\Controller;


use Ojs\Common\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

class JournalControllerTest extends BaseTestCase
{
    public function testJournalSummary()
    {
        $this->client->request('GET', $this->router->generate('analytics_summary_journals_all'));
        /** @var Response $response */
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testJournalViews()
    {
        $this->client->request('GET', $this->router->generate('analytics_views_journal'));
        /** @var Response $response */
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }
}
