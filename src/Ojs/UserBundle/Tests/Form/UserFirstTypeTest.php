<?php

namespace Ojs\UserBundle\Tests\Form;

use Ojs\Common\Tests\BaseTypeTestcase;
use Ojs\UserBundle\Form\Type\UserFirstType;

class UserFirstTypeTest extends BaseTypeTestcase
{
    public function testSubmitValidData()
    {
        $this->basicSubmitTest(
            new UserFirstType(),
            [
                'title' => $this->faker->word,
                'firstName' => $this->faker->firstName,
                'lastName' => $this->faker->lastName,
                'username' => $this->faker->userName,
                'password' => $this->faker->word,
                'email' => $this->faker->email,
            ],
            'Ojs\UserBundle\Entity\User'
        );
    }
}
