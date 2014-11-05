<?php

namespace Ojs\JournalBundle\Tests\Controller;

use \Ojs\Common\Helper\TestHelper;

class JournalSectionControllerTest extends TestHelper
{
    public function testStatus()
    {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $this->client->request('GET', '/admin/journal/section/1/show');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

}
