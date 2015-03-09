<?php
/**
 * Date: 13.01.15
 * Time: 16:48
 */

namespace Ojs\UserBundle\Tests\Form;


use Ojs\Common\Tests\BaseTypeTestcase;
use Ojs\UserBundle\Form\RegisterFormType;

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
                'email' => 'z@emre.xyz'
            ],
            'Ojs\UserBundle\Entity\User'
        );
    }

}
