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

        $journal = $this->context->getRoot();

        if (!$journal instanceof Journal) {
            $journal = $journal->getData();
        }

        $qb = $this->em->getRepository(Journal::class);
        $query = $qb->createQueryBuilder('j')
            ->where('j.issn = :value')
            ->orWhere('j.eissn = :value')
            ->setParameter('value',$value);

        if($journal->getId() !== null){
            $query->andWhere('j.id !='.$journal->getId());
        }
        
        $query = $query
            ->getQuery()
            ->getResult();

        if (!empty($value) && $query) {
            $this->context->addViolation($constraint->message);
        }
    }
}
