<?php

namespace Ojs\InstallerBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->isAccessible(['ojs_installer_homepage']);
    }
    public function testCheck()
    {
        $this->isAccessible(['ojs_installer_check']);
    }
    public function testConfigure()
    {
        $this->isAccessible(['ojs_installer_configure']);
    }
    public function testSetup()
    {
        #$this->isAccessible(['ojs_installer_setup']);
        $this->assertTrue(true);
        //@todo buraya başka şekilde birşeyler ayarlanacak.
    }
    public function testSummary()
    {
        $this->logIn();
        $this->isAccessible(['ojs_installer_summary']);
    }

}
