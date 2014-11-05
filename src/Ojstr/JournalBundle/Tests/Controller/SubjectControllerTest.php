<?php

namespace Ojstr\JournalBundle\Tests\Controller;

use \Ojs\Common\Helper\TestHelper;

class SubjectControllerTest extends TestHelper
{
    public function testStatus()
    {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $this->client->request('GET', '/admin/subject/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request('GET', '/admin/subject/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

}
