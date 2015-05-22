<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;

class JournalContactControllerTest extends BaseTestCase
{

    public function testStatus()
    {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $this->client->request('GET', '/admin/journalcontact/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
