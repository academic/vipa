<?php

namespace Vipa\JournalBundle\Validator\Constraints;

use Vipa\JournalBundle\Validator\ArticleStatusValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ArticleStatus extends Constraint
{
    public $message = 'One or more of emails are not available or valid.';

    public function validatedBy()
    {
        return ArticleStatusValidator::class;
    }
}
