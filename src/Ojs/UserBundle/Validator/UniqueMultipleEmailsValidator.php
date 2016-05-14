<?php

namespace Ojs\UserBundle\Validator;

use Doctrine\ORM\EntityManager;
use Ojs\UserBundle\Validator\Constraints\UniqueMultipleEmails;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\EmailValidator;
use Doctrine\ORM\Query\Expr;

/**
 * @Annotation
 */
class UniqueMultipleEmailsValidator extends EmailValidator
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
        parent::__construct();
        $this->em = $em;
    }

    /**
     * @param mixed $value
     * @param Constraint|UniqueMultipleEmails $constraint
     * @return mixed
     */
    public function validate($value, Constraint $constraint)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->from('OjsUserBundle:User', 'user')
            ->select('user.email')
            ->leftJoin('OjsUserBundle:MultipleMail', 'm', Expr\Join::WITH, 'm.mail = :mail')
            ->where('user.email = :mail')
            ->setParameter('mail', $value);

        $result = $query->getQuery()->getArrayResult();
        if (!empty($result)) {
            $this->context->addViolation($constraint->message);
        }
    }
}
