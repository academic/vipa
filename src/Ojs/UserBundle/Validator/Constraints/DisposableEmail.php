<?php

namespace Ojs\UserBundle\Validator\Constraints;

use Ojs\UserBundle\Validator\DisposableEmailValidator;
use Symfony\Component\Validator\Constraints\Email;

/**
 * @Annotation
 */
class DisposableEmail extends Email
{
    public $message = 'One or more of emails are not available or valid.';

    public function validatedBy()
    {
        return DisposableEmailValidator::class;
    }
}
