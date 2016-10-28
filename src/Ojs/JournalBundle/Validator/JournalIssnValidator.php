<?php

namespace Ojs\JournalBundle\Validator;

use Doctrine\ORM\EntityManager;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Validator\Constraints\JournalIssn;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class JournalIssnValidator extends ConstraintValidator
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @JournalIssnValidator() constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param mixed $value
     * @param Constraint|JournalIssn $constraint
     * @return mixed
     */
    public function validate($value, Constraint $constraint)
    {
        if (!empty($value) && $this->em->getRepository(Journal::class)->findBy(['issn' => $value, 'eissn' => $value])) {
            $this->context->addViolation($constraint->message);
        }
    }
}
