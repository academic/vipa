<?php

namespace Ojstr\JournalBundle\Tests\Controller;

use \Ojstr\Common\Helper\TestHelper;

class JournalContactControllerTest extends TestHelper {

    public function testStatus() {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $this->client->request('GET', '/admin/journalcontact/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

}
