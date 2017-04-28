<?php


namespace Vipa\UserBundle\Validator;

use ContentFarm\DisposableEmail\DisposableEmailService;
use Vipa\UserBundle\Validator\Constraints\DisposableEmail;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class DisposableEmailValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint|DisposableEmail $constraint
     * @return mixed
     */
    public function validate($value, Constraint $constraint)
    {

        $disposableEmailService = new DisposableEmailService();
        $disposableEmailService->mail = $value;


        if ($disposableEmailService->isDisposableEmail()) {
            $this->context->addViolation($constraint->message);
        }

    }
}