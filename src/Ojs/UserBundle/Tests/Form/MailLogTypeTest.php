<?php

namespace Ojs\UserBundle\Tests\Form;

use Ojs\Common\Tests\BaseTypeTestcase;
use Ojs\AdminBundle\Form\Type\MailLogType;

class MailLogTypeTest extends BaseTypeTestcase
{
    public function testSubmitValidData()
    {
        $this->basicSubmitTest(
            new MailLogType(),
            [
                'mailObject' => $this->faker->text(),
                'recipientEmail' => $this->faker->email,
            ],
            'Ojs\UserBundle\Entity\MailLog'
        );
    }
}
