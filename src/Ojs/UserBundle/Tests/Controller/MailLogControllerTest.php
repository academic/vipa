<?php

namespace Ojs\UserBundle\Tests\Controller;

class MailLogControllerTest extends \Ojs\Common\Helper\TestHelper
{
    public function testStatus()
    {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));
        $this->client->request('GET', '/admin/maillog/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
