<?php

namespace Vipa\ApiBundle\Tests\Controller;

use Vipa\ApiBundle\Tests\ApiBaseTestCase;
use Symfony\Component\BrowserKit\Cookie;

/**
 * Class AnalyticsRestControllerTest
 * @package Vipa\ApiBundle\Tests\Controller
 */
class AnalyticsRestControllerTest extends ApiBaseTestCase
{
    public function testArticleViewAction()
    {
        $token = $this->getContainer()
            ->get('security.csrf.token_manager')
            ->getToken('article_view');

        $client = $this->client;
        $session = $client->getContainer()->get('session');
        $session->set('_csrf/article_view', $token->getValue());
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);

        $client->request(
            'POST',
            '/api/v1/stats/article/1/view',
            [
                'token' => $token,
            ],
            [],
            ['HTTP_ACCEPT' => 'application/json']
        );

        $this->assertStatusCode(200, $client);
    }

    public function testIssueViewAction()
    {
        $token = $this->getContainer()
            ->get('security.csrf.token_manager')
            ->getToken('issue_view');

        $client = $this->client;
        $session = $client->getContainer()->get('session');
        $session->set('_csrf/issue_view', $token->getValue());
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);

        $client->request(
            'POST',
            '/api/v1/stats/issue/1/view',
            [
                'token' => $token,
            ],
            [],
            ['HTTP_ACCEPT' => 'application/json']
        );

        $this->assertStatusCode(200, $client);
    }

    public function testJournalViewAction()
    {
        $token = $this->getContainer()
            ->get('security.csrf.token_manager')
            ->getToken('journal_view');

        $client = $this->client;
        $session = $client->getContainer()->get('session');
        $session->set('_csrf/journal_view', $token->getValue());
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);

        $client->request(
            'POST',
            '/api/v1/stats/journal/1/view',
            [
                'token' => $token,
            ],
            [],
            ['HTTP_ACCEPT' => 'application/json']
        );

        $this->assertStatusCode(200, $client);
    }
}
