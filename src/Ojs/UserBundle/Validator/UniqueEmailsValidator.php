<?php

namespace Ojs\UserBundle\Validator;

use Doctrine\ORM\EntityManager;
use Ojs\UserBundle\Validator\Constraints\UniqueEmails;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\EmailValidator;

/**
 * @Annotation
 */
class UniqueEmailsValidator extends EmailValidator
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
     * @param Constraint|UniqueEmails $constraint
     * @return mixed
     */
    public function validate($value, Constraint $constraint)
    {
        $builder = $this->em->createQueryBuilder();
        $query = $builder->from('OjsUserBundle:User', 'user')->select('user.email');

        foreach ($value as $email) {
            parent::validate($email, $constraint);
            $query->where(
                $query->expr()->andX(
                    "NOT user.extraEmails LIKE '%".implode(',', $value)."%'",
                    $query->expr()->orX(
                        "user.email = '".$email."'",
                        "user.extraEmails LIKE '%".$email."%'"
                    )
                )
            );
        }

        $result = $query->getQuery()->getArrayResult();

        if (!empty($result)) {
            $this->context->addViolation($constraint->message);
        }
    }
}
