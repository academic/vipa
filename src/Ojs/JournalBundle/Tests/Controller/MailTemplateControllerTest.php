<?php

namespace Ojs\JournalBundle\Tests\Controller;

use \Ojs\Common\Tests\BaseTestCase;

class MailTemplateControllerTest extends BaseTestCase
{

    public function testStatus()
    {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $this->client->request('GET', '/admin/mailtemplate/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request('GET', '/admin/mailtemplate/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

}
