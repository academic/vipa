<?php

namespace Vipa\UserBundle\Validator\Constraints;

use Vipa\UserBundle\Validator\UniqueMultipleEmailsValidator;
use Symfony\Component\Validator\Constraints\Email;

/**
 * @Annotation
 */
class UniqueMultipleEmails extends Email
{
    public $message = 'One or more of emails are not available or valid.';

    public function validatedBy()
    {
        return UniqueMultipleEmailsValidator::class;
    }
}
