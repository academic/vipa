<?php

namespace Ojs\SiteBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class IssueControllerTest extends BaseTestCase
{
    public function testIssuePage()
    {
        $client = static::makeClient(array(), array('HTTP_HOST' => 'www.ojs.dev'));
        $client->request('GET', '/intro/issue/1');
        $this->assertStatusCode(200, $client);
    }

}

