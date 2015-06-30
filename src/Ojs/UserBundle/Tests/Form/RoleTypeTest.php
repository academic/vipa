<?php

namespace Ojs\UserBundle\Tests\Form;

use Ojs\Common\Tests\BaseTypeTestcase;
use Ojs\UserBundle\Form\Type\RoleType;

class RoleTypeTest extends BaseTypeTestcase
{
    public function testSubmitValidData()
    {
        $this->basicSubmitTest(
            new RoleType(),
            [
                'name' => $this->faker->word,
                'role' => $this->faker->word,
            ],
            'Ojs\UserBundle\Entity\Role'
        );
    }
}
