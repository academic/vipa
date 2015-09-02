<?php

namespace Ojs\UserBundle\Tests\Form;

use Ojs\CoreBundle\Tests\BaseTypeTestcase;
use Ojs\UserBundle\Form\Type\RegisterFormType;

class RegisterFormTypeTest extends BaseTypeTestcase
{
    public function testSubmitValidData()
    {
        $this->basicSubmitTest(
            new RegisterFormType(),
            [
                'firstName' => 'Emre',
                'lastName' => 'Yılmaz',
                'username' => 'emreyilmaz',
                'password' => '123123',
                'email' => 'z@emre.xyz',
            ],
            'Ojs\UserBundle\Entity\User'
        );
    }
}
