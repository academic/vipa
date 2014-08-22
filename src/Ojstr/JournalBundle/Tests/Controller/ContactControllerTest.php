<?php

namespace Ojstr\JournalBundle\Tests\Controller;

use \Ojstr\Common\Helper\TestHelper;

class ContactControllerTest extends TestHelper {

    public function testStatus() {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $this->client->request('GET', '/admin/contact/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request('GET', '/admin/contact/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

}
