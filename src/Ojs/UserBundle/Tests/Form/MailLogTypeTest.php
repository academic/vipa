<?php

namespace Ojs\UserBundle\Tests\Form;

use Ojs\AdminBundle\Form\Type\MailLogType;
use Ojs\CoreBundle\Tests\BaseTypeTestcase;

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
