<?php

namespace Vipa\UserBundle\Validator\Constraints;

use Vipa\UserBundle\Validator\DisposableEmailValidator;
use Symfony\Component\Validator\Constraints\Email;

/**
 * @Annotation
 */
class DisposableEmail extends Email
{
    public $message = 'One or more of emails are not available or valid domain.';

    public function validatedBy()
    {
        return DisposableEmailValidator::class;
    }
}
