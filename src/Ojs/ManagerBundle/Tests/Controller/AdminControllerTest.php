<?php

namespace Ojs\ManagerBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;

class AdminControllerTest extends BaseTestCase
{
    public function testAdminDashboard()
    {
        $this->logIn();
        $this->assertTrue($this->isAccessible(['dashboard_admin']));
    }

}
