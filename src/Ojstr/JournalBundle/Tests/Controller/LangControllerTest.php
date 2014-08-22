<?php

namespace Ojstr\JournalBundle\Tests\Controller;

use \Ojstr\Common\Helper\TestHelper;

class LangControllerTest extends TestHelper {

    public function testStatus() {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $this->client->request('GET', '/admin/language/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request('GET', '/admin/language/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

}
