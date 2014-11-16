<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;

class JournalControllerTest extends BaseTestCase
{


    public function testStatus()
    {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $this->client->request('GET', '/admin/journal/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request('GET', '/admin/journal/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

}
