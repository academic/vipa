<?php

namespace Ojs\UserBundle\Validator\Constraints;

use Ojs\UserBundle\Validator\UniqueEmailsValidator;
use Symfony\Component\Validator\Constraints\Email;

/**
 * @Annotation
 */
class UniqueEmails extends Email
{
    public $message = 'One or more of emails are not available or valid.';

    public function validatedBy()
    {
        return UniqueEmailsValidator::class;
    }
}
