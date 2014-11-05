<?php

namespace Ojstr\JournalBundle\Tests\Controller;

use \Ojs\Common\Helper\TestHelper;

class ContactTypesControllerTest extends TestHelper
{
    public function testStatus()
    {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $this->client->request('GET', '/admin/contacttypes/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request('GET', '/admin/contacttypes/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

}
