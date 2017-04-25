<?php

namespace Vipa\UserBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class ChangePasswordControllerTest extends BaseTestCase
{
    public function testChangePassword()
    {
        $client = $this->client;
        $crawler = $client->request('GET', '/profile/change-password', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ));
        $this->assertStatusCode(200, $client);
    }
}