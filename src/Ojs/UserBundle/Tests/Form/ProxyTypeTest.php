<?php
/**
 * Date: 13.01.15
 * Time: 18:43
 */

namespace Ojs\UserBundle\Tests\Form;


use Ojs\Common\Tests\BaseTypeTestcase;
use Ojs\UserBundle\Form\ProxyType;

class ProxyTypeTest extends BaseTypeTestcase
{
    public function testSubmitValidData()
    {
        $this->basicSubmitTest(
            new ProxyType(),
            [
                'proxyUserId' => 1,
                'clientUserId' => 1
            ],
            'Ojs\UserBundle\Entity\Proxy');
    }
}
