<?php

namespace Ojs\ManagerBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;

class ManagerControllerTest extends BaseTestCase
{

    public function testManagerDashboard()
    {
        $this->logIn(null,['ROLE_EDITOR']);
        $this->assertTrue($this->isAccessible(['dashboard_editor']));

        $this->assertTrue($this->isAccessible(['editor_show_my_journals']));
    }

}
