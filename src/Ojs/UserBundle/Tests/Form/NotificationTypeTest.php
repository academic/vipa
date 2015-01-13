<?php
/** 
 * Date: 13.01.15
 * Time: 18:38
 */

namespace Ojs\UserBundle\Tests\Form;


use Ojs\Common\Tests\BaseTypeTestcase;
use Ojs\UserBundle\Form\NotificationType;

class NotificationTypeTest extends BaseTypeTestcase {
 public function testSubmitValidData(){
         $this->basicSubmitTest(
             new NotificationType(),
             [
                 'senderId'=>1,
                 'recipientId'=>1,
                 'entityId'=>1,
                 'entityName'=>$this->faker->domainName,
                 'isRead'=>$this->faker->boolean(),
                 'text'=>$this->faker->text(100),
                 'action'=>$this->faker->text(10),
                 'level'=>$this->faker->text(10)
             ],
             'Ojs\UserBundle\Entity\Notification');
     }
}
