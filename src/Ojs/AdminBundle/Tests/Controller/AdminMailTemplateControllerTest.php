<?php

namespace Ojs\AdminBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class AdminMailTemplateControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $client->request('GET', '/admin/mail-template/');

        $this->assertStatusCode(200, $client);
    }
}