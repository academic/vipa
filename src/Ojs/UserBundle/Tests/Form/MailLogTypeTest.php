<?php
/** 
 * Date: 13.01.15
 * Time: 18:35
 */

namespace Ojs\UserBundle\Tests\Form;


use Ojs\Common\Tests\BaseTypeTestcase;
use Ojs\UserBundle\Form\MailLogType;

class MailLogTypeTest extends BaseTypeTestcase {
 public function testSubmitValidData(){
         $this->basicSubmitTest(
             new MailLogType(),
             [
                'mailObject'=>$this->faker->text(),
                 'recipientEmail'=>$this->faker->email
             ],
             'Ojs\UserBundle\Entity\MailLog');
     }
}
