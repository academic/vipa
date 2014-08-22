<?php

namespace Ojstr\JournalBundle\Tests\Controller;

use \Ojstr\Common\Helper\TestHelper;

class ThemeControllerTest extends TestHelper {

    public function testStatus() {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $this->client->request('GET', '/admin/theme/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request('GET', '/admin/theme/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

}
