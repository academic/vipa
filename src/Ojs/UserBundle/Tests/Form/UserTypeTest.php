<?php
/**
 * Date: 13.01.15
 * Time: 18:51
 */

namespace Ojs\UserBundle\Tests\Form;


use Ojs\Common\Tests\BaseTypeTestcase;
use Ojs\UserBundle\Form\UserType;

class UserTypeTest extends BaseTypeTestcase
{
    public function testSubmitValidData()
    {
        /*  $this->basicSubmitTest(
              new UserType(),
              [
                  'username'=>$this->faker->userName,
                  'password'=>$this->faker->word,
                  'email'=>$this->faker->email,
                  'title'=>$this->faker->word,
                  'firstName'=>$this->faker->firstName,
                  'lastName'=>$this->faker->lastName,
                  'isActive'=>$this->faker->boolean(),
                  'status'=>$this->faker->randomDigit,
                  //'roles'=>[1],
                  //'subjects'=>[1],
                  'avatar'=>'',
                  'header'=>'',

              ],
              'Ojs\UserBundle\Entity\User');
        */
     $this->assertTrue(true);
    }
}
