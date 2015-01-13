<?php
/**
 * Date: 13.01.15
 * Time: 18:44
 */

namespace Ojs\UserBundle\Tests\Form;


use Ojs\Common\Tests\BaseTypeTestcase;
use Ojs\UserBundle\Form\RoleType;

class RoleTypeTest extends BaseTypeTestcase
{
    public function testSubmitValidData()
    {
        $this->basicSubmitTest(
            new RoleType(),
            [
                'name' => $this->faker->word,
                'role' => $this->faker->word,
                'isSystemRole' => $this->faker->boolean()
            ],
            'Ojs\UserBundle\Entity\Role');
    }
}
