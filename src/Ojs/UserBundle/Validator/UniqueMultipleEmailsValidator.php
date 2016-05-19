<?php

namespace Ojs\UserBundle\Validator;

use Doctrine\ORM\EntityManager;
use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Entity\MultipleMail;
use Ojs\UserBundle\Validator\Constraints\UniqueMultipleEmails;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class UniqueMultipleEmailsValidator extends ConstraintValidator
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * UniqueEmailsValidator constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param mixed $value
     * @param Constraint|UniqueMultipleEmails $constraint
     * @return mixed
     */
    public function validate($value, Constraint $constraint)
    {
        if ($this->em->getRepository(MultipleMail::class)->findBy(['mail' => $value])) {
            $this->context->addViolation($constraint->message);
        }

        if ($this->em->getRepository(User::class)->findBy(['email' => $value])) {
            $this->context->addViolation($constraint->message);
        }
    }
}
