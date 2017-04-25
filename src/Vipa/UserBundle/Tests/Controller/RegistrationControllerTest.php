<?php

namespace Vipa\UserBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class RegistrationControllerTest extends BaseTestCase
{
    public function testRegister()
    {
        $client = $this->client;
        $crawler = $client->request('GET', '/register/');
        $this->assertStatusCode(200, $client);
    }
}