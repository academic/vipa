<?php

namespace Ojs\UserBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;

class MailLogControllerTest extends BaseTestCase
{
    public function testStatus()
    {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));
        $this->client->request('GET', '/admin/maillog/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
