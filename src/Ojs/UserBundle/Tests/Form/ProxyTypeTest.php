<?php

namespace Ojs\UserBundle\Tests\Form;

use Ojs\Common\Tests\BaseTypeTestcase;
use Ojs\AdminBundle\Form\Type\ProxyType;

class ProxyTypeTest extends BaseTypeTestcase
{
    public function testSubmitValidData()
    {
        $this->basicSubmitTest(
            new ProxyType(),
            [
                'proxyUserId' => 1,
                'clientUserId' => 1,
            ],
            'Ojs\UserBundle\Entity\Proxy'
        );
    }
}
