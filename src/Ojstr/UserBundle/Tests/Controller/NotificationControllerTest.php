<?php

namespace Ojstr\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Ojstr\Common\Helper\TestHelper;

class NotificationControllerTest extends TestHelper {

    public function testStatus() {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $this->client->request('GET', '/admin/notification/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

}
