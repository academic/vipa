<?php


namespace Ojs\UserBundle\Validator;

use ContentFarm\DisposableEmail\DisposableEmailService;
use Ojs\UserBundle\Entity\MultipleMail;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class DisposableEmailValidator extends ConstraintValidator
{


    /**
     * DisposableEmailValidator constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param mixed $value
     * @param Constraint|DisposableEmail $constraint
     * @return mixed
     */
    public function validate($value, Constraint $constraint)
    {

        $disposableEmailService = new DisposableEmailService;
        $disposableEmailService->mail = $value;


        if ($disposableEmailService->isDisposableEmail()) {
            $this->context->addViolation($constraint->message);
        }

    }
}