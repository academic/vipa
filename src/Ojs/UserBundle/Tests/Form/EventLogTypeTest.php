<?php
/** 
 * Date: 13.01.15
 * Time: 18:22
 */

namespace Ojs\UserBundle\Tests\Form;


use Ojs\Common\Tests\BaseTypeTestcase;
use Ojs\UserBundle\Form\EventLogType;

class EventLogTypeTest extends BaseTypeTestcase {
    public function testSubmitValidData(){
        $this->basicSubmitTest(
            new EventLogType(),
            [
                'eventInfo'=>$this->faker->text(),
                'eventDate'=>new \DateTime(),
                'ip'=>$this->faker->ipv4,
                'userId'=>1
            ],
            'Ojs\UserBundle\Entity\EventLog');
    }
}
